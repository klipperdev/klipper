<?php

/*
 * This file is part of the Klipper package.
 *
 * (c) François Pluchino <francois.pluchino@klipper.dev>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Klipper\Component\Content;

use Klipper\Component\Content\Downloader\DownloaderInterface;
use Klipper\Component\Content\ImageManipulator\Config;
use Klipper\Component\Content\Uploader\Exception\InvalidArgumentException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\UnsupportedMediaTypeHttpException;

/**
 * @author François Pluchino <francois.pluchino@klipper.dev>
 */
interface ContentManagerInterface
{
    /**
     * Get the uploader name by payload.
     *
     * @param mixed $payload The payload
     */
    public function getUploaderName($payload): ?string;

    /**
     * Upload a file.
     *
     * @param string     $uploaderName The uploader name
     * @param null|mixed $payload      The payload
     */
    public function upload(string $uploaderName, $payload = null): Response;

    /**
     * Download the file.
     *
     * @param string      $uploaderName       The name of the uploader used
     * @param null|string $path               The file path
     * @param null|string $contentDisposition The content disposition
     * @param array       $headers            The custom headers
     * @param string      $mode               The download mode
     *
     * @throws NotFoundHttpException
     * @throws UnsupportedMediaTypeHttpException
     * @throws InvalidArgumentException
     */
    public function download(string $uploaderName, ?string $path, ?string $contentDisposition = null, array $headers = [], string $mode = DownloaderInterface::MODE_AUTO): Response;

    /**
     * Download the image file.
     *
     * @param string      $uploaderName       The name of the uploader used
     * @param null|string $path               The image file path
     * @param null|string $contentDisposition The content disposition
     * @param array       $headers            The custom headers
     *
     * @throws NotFoundHttpException
     * @throws UnsupportedMediaTypeHttpException
     * @throws InvalidArgumentException
     */
    public function downloadImage(string $uploaderName, ?string $path, ?string $contentDisposition = null, array $headers = []): Response;

    /**
     * Copy the file.
     *
     * @param string $uploaderName The name of the uploader used
     * @param string $originPath   The origin path
     * @param string $targetPath   The target path
     */
    public function copy(string $uploaderName, string $originPath, string $targetPath): bool;

    /**
     * Remove the file.
     *
     * @param string          $uploaderName The name of the uploader used
     * @param string|string[] $path         The one path or multiple paths
     *
     * @throws InvalidArgumentException
     */
    public function remove(string $uploaderName, $path): bool;

    /**
     * Build the absolute path.
     *
     * @param string $uploaderName The name of the uploader used
     * @param string $relativePath The relative path
     */
    public function buildAbsolutePath(string $uploaderName, string $relativePath): string;

    /**
     * Build the relative path.
     *
     * @param string $uploaderName The name of the uploader used
     * @param string $absolutePath The absolute path
     */
    public function buildRelativePath(string $uploaderName, string $absolutePath): string;

    /**
     * Build the image manipulator config.
     *
     * @param null|Request $request The request
     */
    public function buildConfig(?Request $request = null): Config;
}
