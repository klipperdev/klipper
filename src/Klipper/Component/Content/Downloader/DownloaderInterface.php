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

use Klipper\Component\Content\ImageManipulator\Config;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\UnsupportedMediaTypeHttpException;

/**
 * @author François Pluchino <francois.pluchino@klipper.dev>
 */
interface DownloaderInterface
{
    public const MODE_AUTO = 'auto';

    public const MODE_FORCE_ORIGINAL = 'force_original';

    public const MODE_FORCE_IMAGE_MANIPULATOR = 'force_image_manipulator';

    /**
     * Download the file.
     *
     * @param null|string $path               The file path
     * @param null|string $contentDisposition The content disposition
     * @param array       $headers            The custom headers
     * @param string      $mode               The download mode
     *
     * @throws NotFoundHttpException
     * @throws UnsupportedMediaTypeHttpException
     */
    public function download(?string $path, ?string $contentDisposition = null, array $headers = [], string $mode = self::MODE_AUTO): Response;

    /**
     * Download the image file.
     *
     * @param null|string $path               The image file path
     * @param null|string $contentDisposition The content disposition
     * @param array       $headers            The custom headers
     *
     * @throws NotFoundHttpException
     * @throws UnsupportedMediaTypeHttpException
     */
    public function downloadImage(?string $path, ?string $contentDisposition = null, array $headers = []): Response;

    /**
     * Build the image manipulator config.
     *
     * @param null|Request $request The request
     */
    public function buildConfig(?Request $request = null): Config;
}
