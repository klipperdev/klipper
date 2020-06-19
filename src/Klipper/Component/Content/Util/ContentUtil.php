<?php

/*
 * This file is part of the Klipper package.
 *
 * (c) François Pluchino <francois.pluchino@klipper.dev>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Klipper\Component\Content\Util;

use Klipper\Component\Content\Uploader\UploaderConfigurationInterface;
use Symfony\Component\Filesystem\Filesystem;

/**
 * @author François Pluchino <francois.pluchino@klipper.dev>
 */
class ContentUtil
{
    /**
     * @param string|UploaderConfigurationInterface $basePath The base path
     * @param string                                $path     The file path
     */
    public static function getAbsolutePath($basePath, string $path): string
    {
        if ($basePath instanceof UploaderConfigurationInterface) {
            $basePath = $basePath->getPath();
        }

        return rtrim((string) $basePath, '/').'/'.ltrim($path, '/');
    }

    public static function getRelativePath(Filesystem $fs, $basePath, string $absolutePath): string
    {
        if ($basePath instanceof UploaderConfigurationInterface) {
            $basePath = $basePath->getPath();
        }

        $basePath = rtrim((string) static::formatPath($basePath), '/');
        $absolutePath = static::formatPath($absolutePath);

        return '/'.rtrim($fs->makePathRelative($absolutePath, $basePath), '/');
    }

    public static function formatPath(string $path): string
    {
        return str_replace('\\', '/', $path);
    }
}
