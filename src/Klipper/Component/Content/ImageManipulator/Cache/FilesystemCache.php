<?php

/*
 * This file is part of the Klipper package.
 *
 * (c) François Pluchino <francois.pluchino@klipper.dev>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Klipper\Component\Content\ImageManipulator\Cache;

use Klipper\Component\Content\ImageManipulator\ConfigInterface;
use Klipper\Component\Content\ImageManipulator\Exception\InvalidArgumentException;
use Klipper\Component\Content\ImageManipulator\Image;
use Symfony\Component\Filesystem\Filesystem;

/**
 * @author François Pluchino <francois.pluchino@klipper.dev>
 */
class FilesystemCache implements CacheInterface
{
    protected string $cachePath;

    protected Filesystem $filesystem;

    /**
     * @param string          $cachePath  The path of the cache
     * @param null|Filesystem $filesystem The filesystem
     */
    public function __construct(string $cachePath, ?Filesystem $filesystem = null)
    {
        $this->cachePath = rtrim(str_replace('\\', '/', $cachePath), '/');
        $this->filesystem = $filesystem ?? new Filesystem();
    }

    public function has(string $path, ConfigInterface $config): bool
    {
        return $this->filesystem->exists($this->getCachePath($path, $config));
    }

    /**
     * @param mixed $resource
     *
     * @throws
     */
    public function set(string $path, ConfigInterface $config, $resource): bool
    {
        $cachePath = $this->getCachePath($path, $config);
        $meta = stream_get_meta_data($resource);

        if (!is_writable($meta['uri'])) {
            throw new InvalidArgumentException('The resource of the image manipulator is not writable');
        }

        if (!file_exists($baseCachePath = \dirname($cachePath))) {
            $this->filesystem->mkdir($baseCachePath);
        }

        return \is_int(file_put_contents($cachePath, $resource));
    }

    public function get(string $path, ConfigInterface $config)
    {
        $cachePath = $this->getCachePath($path, $config);

        try {
            if (file_exists($cachePath)) {
                $stream = @fopen($cachePath, 'r');

                if (\is_resource($stream)) {
                    return new Image($stream, mime_content_type($cachePath));
                }
            }
        } catch (\Throwable $e) {
            // do nothing
        }

        return false;
    }

    public function clear(string $path): bool
    {
        $this->filesystem->remove($this->getBaseCachePath($path));

        return true;
    }

    /**
     * Get the full name for the key of cache.
     *
     * @param string          $path   The image path
     * @param ConfigInterface $config The config
     */
    protected function getCachePath(string $path, ConfigInterface $config): string
    {
        $ext = $config->getExtension() ?? pathinfo($path, PATHINFO_EXTENSION);

        return $this->getBaseCachePath($path)
            .$config->getMode()
            .'-s'.$config->getScale()
            .'-w'.$config->getWidth()
            .'-h'.$config->getHeight()
            .'.'.$ext;
    }

    /**
     * Get the base name for the key of cache.
     *
     * @param string $path The image path
     */
    protected function getBaseCachePath(string $path): string
    {
        $name = $this->cleanName($path);

        if ($this->filesystem->isAbsolutePath($path) && $this->filesystem->isAbsolutePath($this->cachePath)) {
            $basePath = $this->cachePath;
            $diff = $this->filesystem->makePathRelative(\dirname($path), $this->cachePath);
            preg_match_all('/\.\.\//', $diff, $matches, PREG_SET_ORDER);

            if (($countPath = \count($matches)) > 0) {
                $basePath = \dirname($basePath, $countPath);
            }

            $name = rtrim($this->filesystem->makePathRelative($path, $basePath), '/');
        }

        return sprintf(
            '%s/%s/',
            $this->cachePath,
            $name
        );
    }

    /**
     * Clean the name.
     */
    protected function cleanName(string $name): string
    {
        return str_replace(['\\', '://'], '/', $name);
    }
}
