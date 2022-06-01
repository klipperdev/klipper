<?php

/*
 * This file is part of the Klipper package.
 *
 * (c) François Pluchino <francois.pluchino@klipper.dev>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Klipper\Component\Content\Uploader\Adapter\FormData;

use Klipper\Component\Content\Uploader\Adapter\AdapterInterface;
use Klipper\Component\Content\Uploader\Event\UploadFileCompletedEvent;
use Klipper\Component\Content\Uploader\Event\UploadFileCompleteEvent;
use Klipper\Component\Content\Uploader\Event\UploadFileCreatedEvent;
use Klipper\Component\Content\Uploader\Event\UploadFileMoveEvent;
use Klipper\Component\Content\Uploader\File\FilesystemFile;
use Klipper\Component\Content\Uploader\Namer\NamerManagerInterface;
use Klipper\Component\Content\Uploader\UploaderConfigurationInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\FileBag;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;

/**
 * @author François Pluchino <francois.pluchino@klipper.dev>
 */
class FormDataAdapter implements AdapterInterface
{
    private EventDispatcherInterface $dispatcher;

    private NamerManagerInterface $namerManager;

    public function __construct(
        EventDispatcherInterface $dispatcher,
        NamerManagerInterface $namerManager
    ) {
        $this->dispatcher = $dispatcher;
        $this->namerManager = $namerManager;
    }

    public function supports(Request $request, UploaderConfigurationInterface $uploader): bool
    {
        return Request::METHOD_POST === $request->getMethod()
            && false !== strpos($request->headers->get('Content-Type'), 'multipart/form-data');
    }

    /**
     * @param null|mixed $payload
     *
     * @throws
     */
    public function upload(Request $request, UploaderConfigurationInterface $uploader, $payload = null): Response
    {
        $files = $this->getFiles($request->files);

        if (empty($files)) {
            throw new BadRequestHttpException('No file was sent');
        }

        if (\count($files) > 1 || !$this->validateUploadSize($uploader, $files)) {
            throw new HttpException(
                Response::HTTP_REQUEST_ENTITY_TOO_LARGE,
                Response::$statusTexts[Response::HTTP_REQUEST_ENTITY_TOO_LARGE]
            );
        }

        $file = new FilesystemFile($files[0]);
        $originalName = $file->getClientOriginalName();
        $this->dispatcher->dispatch(new UploadFileCreatedEvent($uploader, $request, $file, $payload));

        $namer = $this->namerManager->get($uploader->getNamer());
        $name = null !== $namer
            ? $namer->name($file)
            : ($originalName ?? uniqid('', false).'.'.$file->getExtension());

        $this->dispatcher->dispatch(new UploadFileMoveEvent($uploader, $request, $file, $name, $payload));

        $movedFile = $file->move($uploader->getPath(), $name);
        $newFile = new FilesystemFile($movedFile, $originalName);

        $this->dispatcher->dispatch(new UploadFileCompleteEvent($uploader, $request, $newFile, $payload));

        $completedEvent = new UploadFileCompletedEvent($uploader, $request, $newFile, $payload);
        $this->dispatcher->dispatch($completedEvent);

        foreach ($request->getAcceptableContentTypes() as $contentType) {
            if ('json' === $request->getFormat($contentType)) {
                $resPayload = $completedEvent->getResponsePayload();
                $res = [
                    'success' => true,
                ];

                if (!empty($resPayload)) {
                    $res['payload'] = $resPayload;
                }

                return new JsonResponse($res);
            }
        }

        return new Response(null, Response::HTTP_NO_CONTENT);
    }

    /**
     * Verify the max upload size.
     *
     * @param UploaderConfigurationInterface $uploader The uploader
     * @param UploadedFile[]                 $files    The files
     */
    protected function validateUploadSize(UploaderConfigurationInterface $uploader, array $files): bool
    {
        $maxUploadSize = $uploader->getMaxSize();
        $size = 0;
        $res = true;

        if ($maxUploadSize > 0) {
            foreach ($files as $file) {
                $size += $file->getSize();

                if ($size > $maxUploadSize) {
                    $res = false;

                    break;
                }
            }
        }

        return $res;
    }

    /**
     *  Flattens a given file bag to extract all files.
     *
     * @param FileBag $bag The file bag to use
     *
     * @return UploadedFile[] An array of files
     */
    protected function getFiles(FileBag $bag): array
    {
        $files = [];
        $fileBag = $bag->all();
        $fileIterator = new \RecursiveIteratorIterator(
            new \RecursiveArrayIterator($fileBag),
            \RecursiveIteratorIterator::SELF_FIRST
        );

        foreach ($fileIterator as $file) {
            if (null === $file || \is_array($file)) {
                continue;
            }

            $files[] = $file;
        }

        return $files;
    }
}
