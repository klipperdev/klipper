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
use Klipper\Component\Content\ImageManipulator\Image;
use Klipper\Component\Content\ImageManipulator\ImageInterface;

/**
 * @author François Pluchino <francois.pluchino@klipper.dev>
 */
interface CacheInterface
{
    /**
     * Check if the manipulated image for the specific config is present in cache.
     *
     * @param string          $path   The image path
     * @param ConfigInterface $config The config
     */
    public function has(string $path, ConfigInterface $config): bool;

    /**
     * Set the manipulated image in cache.
     *
     * @param string          $path     The image path
     * @param ConfigInterface $config   The config
     * @param resource        $resource The resource
     *
     * @return bool|Image
     */
    public function set(string $path, ConfigInterface $config, $resource): bool;

    /**
     * Get the manipulated image in cache or false if the image with her config does not exist.
     *
     * @param string          $path   The image path
     * @param ConfigInterface $config The config
     *
     * @return false|ImageInterface
     */
    public function get(string $path, ConfigInterface $config);

    /**
     * Clear all manipulated image in cache.
     *
     * @param string $path The image path
     */
    public function clear(string $path): bool;
}
