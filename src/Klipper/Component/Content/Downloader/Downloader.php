<?php

/*
 * This file is part of the Klipper package.
 *
 * (c) François Pluchino <francois.pluchino@klipper.dev>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Klipper\Component\Content\Downloader;

use Klipper\Component\Content\Downloader\Exception\InvalidArgumentException;
use Klipper\Component\Content\ImageManipulator\Config;
use Klipper\Component\Content\ImageManipulator\Exception\InvalidArgumentException as ImageManipulatorInvalidArgumentException;
use Klipper\Component\Content\ImageManipulator\ImageManipulatorInterface;
use Symfony\Component\Filesystem\Exception\FileNotFoundException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\UnsupportedMediaTypeHttpException;

/**
 * @author François Pluchino <francois.pluchino@klipper.dev>
 */
class Downloader implements DownloaderInterface
{
    protected ImageManipulatorInterface $imageManipulator;

    protected RequestStack $requestStack;

    public function __construct(
        ImageManipulatorInterface $imageManipulator,
        RequestStack $requestStack
    ) {
        $this->imageManipulator = $imageManipulator;
        $this->requestStack = $requestStack;
    }

    public function download(
        ?string $path,
        ?string $contentDisposition = null,
        array $headers = [],
        string $mode = self::MODE_AUTO
    ): Response {
        if (empty($path)) {
            throw new NotFoundHttpException(Response::$statusTexts[Response::HTTP_NOT_FOUND]);
        }

        $ext = pathinfo($path, PATHINFO_EXTENSION);
        $config = $this->buildConfig($this->requestStack->getCurrentRequest());
        $configExt = $config->getExtension() ?? $ext;
        $callback = null;

        if ($this->useImageManipulator($mode, $ext, $configExt)) {
            try {
                $image = $this->imageManipulator->create($path, $config);
                $stream = $image->getResource();
                $mimeType = $image->getTypeMime();
                $callback = $image->getCallback();
                $ext = $configExt;
            } catch (FileNotFoundException $e) {
                throw new NotFoundHttpException(Response::$statusTexts[Response::HTTP_NOT_FOUND], $e);
            } catch (ImageManipulatorInvalidArgumentException $e) {
                throw new NotFoundHttpException(Response::$statusTexts[Response::HTTP_NOT_FOUND], $e);
            } catch (InvalidArgumentException $e) {
                throw new UnsupportedMediaTypeHttpException(Response::$statusTexts[Response::HTTP_UNSUPPORTED_MEDIA_TYPE], $e);
            }
        } else {
            $stream = @fopen($path, 'r');

            if (false === $stream) {
                throw new NotFoundHttpException(Response::$statusTexts[Response::HTTP_NOT_FOUND]);
            }

            $mimeType = mime_content_type($path);
        }

        if (null !== $contentDisposition && false === strrpos($contentDisposition, '.')) {
            $contentDisposition = sprintf('%s.%s', $contentDisposition, $ext);
        }

        return $this->downloadStream(
            $stream,
            $mimeType,
            $contentDisposition,
            $headers,
            static function () use ($stream, $callback): void {
                if (null !== $callback && !\is_resource($stream)) {
                    $callback();
                }
            }
        );
    }

    public function downloadImage(
        ?string $path,
        ?string $contentDisposition = null,
        array $headers = []
    ): Response {
        return $this->download(
            $path,
            $contentDisposition,
            $headers,
            DownloaderInterface::MODE_FORCE_IMAGE_MANIPULATOR
        );
    }

    public function buildConfig(?Request $request = null): Config
    {
        $config = new Config();

        if (null !== $request) {
            $config->setMode($request->query->get('m'));
            $config->setScale((int) $request->query->get('s', 1));
            $config->setExtension($request->attributes->get('ext', $request->query->get('ext')));
            $config->setKeepOriginal((bool) $request->query->get('o', false));

            if ($request->query->has('w')) {
                $config->setWidth((int) $request->query->get('w'));
            }

            if ($request->query->has('h')) {
                $config->setHeight((int) $request->query->get('h'));
            }
        }

        return $config;
    }

    /**
     * Check if the image manipulator must be used.
     *
     * @param string $mode      The download mode
     * @param string $ext       The file extension
     * @param string $configExt The config file extension
     */
    protected function useImageManipulator(string $mode, string $ext, string $configExt): bool
    {
        if (static::MODE_AUTO === $mode) {
            return $this->imageManipulator->supports($ext) && $this->imageManipulator->supports($configExt);
        }

        return static::MODE_FORCE_IMAGE_MANIPULATOR === $mode;
    }

    /**
     * Download the stream.
     *
     * @param resource    $stream             The stream resource
     * @param string      $contentType        The content type
     * @param null|string $contentDisposition The content disposition
     * @param array       $headers            The custom headers
     * @param callable    $callback           The callable
     */
    protected function downloadStream(
        $stream,
        string $contentType,
        ?string $contentDisposition = null,
        array $headers = [],
        ?callable $callback = null
    ): Response {
        $request = $this->requestStack->getCurrentRequest();
        $response = new StreamedResponse();

        $stat = fstat($stream);
        $size = $stat['size'];
        $start = 0;
        $end = $size - 1;
        $defaultHeaders = [
            'Content-Type' => $contentType,
            'Accept-Ranges' => 'bytes',
        ];

        if ($request && null !== $contentDisposition
                && null !== $httpRange = $request->server->get('HTTP_RANGE')) {
            if (!preg_match('#bytes=([\\d]+)?-([\\d]+)?(/[\\d]+)?#i', $httpRange)) {
                throw new HttpException(
                    Response::HTTP_REQUESTED_RANGE_NOT_SATISFIABLE,
                    Response::$statusTexts[Response::HTTP_REQUESTED_RANGE_NOT_SATISFIABLE]
                );
            }

            $start = !empty($m[1]) ? (int) $m[1] : null;
            $end = !empty($m[2]) ? (int) $m[2] : $end;

            if ((!$start && !$end)
                    || (null !== $end && $end >= $size)
                    || ($end && $start && $end < $start)) {
                throw new HttpException(
                    Response::HTTP_REQUESTED_RANGE_NOT_SATISFIABLE,
                    Response::$statusTexts[Response::HTTP_REQUESTED_RANGE_NOT_SATISFIABLE]
                );
            }

            if (null === $start) {
                $start = $size - $end;
                --$end;
            }

            $response->setStatusCode(Response::HTTP_PARTIAL_CONTENT);
            $defaultHeaders['Content-Range'] = $start.'-'.$end.'/'.$size;
        }

        $defaultHeaders['Content-Length'] = $end - $start + 1;

        if (null !== $contentDisposition) {
            $defaultHeaders = array_merge($defaultHeaders, [
                'Content-Transfer-Encoding', 'binary',
                'Content-Disposition' => sprintf('attachment; filename="%s"', $contentDisposition),
            ]);
        }

        $response->headers->add(array_merge($defaultHeaders, $headers));

        if ($request && $request->hasSession()
                && null !== $request->getSession()
                && $request->getSession()->isStarted()) {
            $request->getSession()->save();
        }

        $response->setCallback(static function () use ($stream, $callback, $start, $end): void {
            fseek($stream, $start);

            $remainingSize = $end - $start + 1;
            $length = $remainingSize < 4096 ? $remainingSize : 4096;

            while (false !== $datas = fread($stream, $length)) {
                echo $datas;

                $remainingSize -= $length;

                if ($remainingSize <= 0) {
                    break;
                }

                if ($remainingSize < $length) {
                    $length = $remainingSize;
                }
            }

            if (\is_resource($stream)) {
                fclose($stream);
            }

            if (null !== $callback) {
                $callback();
            }
        });

        return $response;
    }
}
