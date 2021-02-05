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

use Klipper\Component\Content\Config\UploaderNameConfigRegistryInterface;
use Klipper\Component\Content\Downloader\DownloaderInterface;
use Klipper\Component\Content\ImageManipulator\Cache\CacheInterface;
use Klipper\Component\Content\ImageManipulator\Config;
use Klipper\Component\Content\Uploader\UploaderInterface;
use Klipper\Component\Content\Util\ContentUtil;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @author François Pluchino <francois.pluchino@klipper.dev>
 */
class ContentManager implements ContentManagerInterface
{
    private UploaderInterface $uploader;

    private DownloaderInterface $downloader;

    private UploaderNameConfigRegistryInterface $configRegistry;

    private Filesystem $fs;

    private ?CacheInterface $imageManipulatorCache;

    public function __construct(
        UploaderInterface $uploader,
        DownloaderInterface $downloader,
        UploaderNameConfigRegistryInterface $configRegistry,
        ?Filesystem $fs = null,
        ?CacheInterface $imageManipulatorCache = null
    ) {
        $this->uploader = $uploader;
        $this->downloader = $downloader;
        $this->configRegistry = $configRegistry;
        $this->fs = $fs ?? new Filesystem();
        $this->imageManipulatorCache = $imageManipulatorCache;
    }

    public function getUploaderName($payload): ?string
    {
        return $this->configRegistry->getUploaderName($payload);
    }

    public function upload(string $uploaderName, $payload = null): Response
    {
        return $this->uploader->upload($uploaderName, $payload);
    }

    public function download(string $uploaderName, ?string $path, ?string $contentDisposition = null, array $headers = [], string $mode = DownloaderInterface::MODE_AUTO): Response
    {
        return $this->downloader->download(
            ContentUtil::getAbsolutePath($this->uploader->get($uploaderName), $path),
            $contentDisposition,
            $headers,
            $mode
        );
    }

    public function downloadImage(string $uploaderName, ?string $path, ?string $contentDisposition = null, array $headers = []): Response
    {
        return $this->downloader->downloadImage(
            ContentUtil::getAbsolutePath($this->uploader->get($uploaderName), $path),
            $contentDisposition,
            $headers
        );
    }

    public function copy(string $uploaderName, string $originPath, $targetPath): bool
    {
        $basePath = $this->uploader->get($uploaderName)->getPath();
        $originFilename = ContentUtil::getAbsolutePath($basePath, $originPath);
        $targetFilename = ContentUtil::getAbsolutePath($basePath, $targetPath);
        $res = true;

        try {
            $this->fs->copy($originFilename, $targetFilename);
        } catch (\Throwable $e) {
            $res = false;
        }

        return $res;
    }

    public function remove(string $uploaderName, $path): bool
    {
        $basePath = $this->uploader->get($uploaderName)->getPath();
        $paths = (array) $path;
        $res = true;

        foreach ($paths as $removePath) {
            $removeFilename = ContentUtil::getAbsolutePath($basePath, $removePath);

            try {
                $this->fs->remove($removeFilename);
            } catch (\Throwable $e) {
                $res = false;
            }

            if (null !== $this->imageManipulatorCache) {
                try {
                    $this->imageManipulatorCache->clear($removeFilename);
                } catch (\Throwable $e) {
                    // no check to optimize request to delete file, so do nothing on error
                }
            }
        }

        return $res;
    }

    public function buildRelativePath(string $uploaderName, string $absolutePath): string
    {
        $basePath = $this->uploader->get($uploaderName)->getPath();

        return ContentUtil::getRelativePath($this->fs, $basePath, $absolutePath);
    }

    public function buildConfig(?Request $request = null): Config
    {
        return $this->downloader->buildConfig($request);
    }
}
