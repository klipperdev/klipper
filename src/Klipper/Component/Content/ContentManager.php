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
use Klipper\Component\Content\Uploader\UploaderInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @author François Pluchino <francois.pluchino@klipper.dev>
 */
class ContentManager implements ContentManagerInterface
{
    private UploaderInterface $uploader;

    private DownloaderInterface $downloader;

    public function __construct(UploaderInterface $uploader, DownloaderInterface $downloader)
    {
        $this->uploader = $uploader;
        $this->downloader = $downloader;
    }

    public function upload(string $uploaderName): Response
    {
        return $this->uploader->upload($uploaderName);
    }

    public function download(string $uploaderName, ?string $path, ?string $contentDisposition = null, array $headers = [], string $mode = DownloaderInterface::MODE_AUTO): Response
    {
        $basePath = $this->uploader->get($uploaderName)->getPath();

        return $this->downloader->download(
            rtrim($basePath, '/').'/'.ltrim($path, '/'),
            $contentDisposition,
            $headers,
            $mode
        );
    }

    public function downloadImage(string $uploaderName, ?string $path, ?string $contentDisposition = null, array $headers = []): Response
    {
        $basePath = $this->uploader->get($uploaderName)->getPath();

        return $this->downloader->downloadImage(
            rtrim($basePath, '/').'/'.ltrim($path, '/'),
            $contentDisposition,
            $headers
        );
    }

    public function buildConfig(?Request $request = null): Config
    {
        return $this->downloader->buildConfig($request);
    }
}
