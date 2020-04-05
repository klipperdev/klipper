<?php

/*
 * This file is part of the Klipper package.
 *
 * (c) François Pluchino <francois.pluchino@klipper.dev>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Klipper\Component\Content\ImageManipulator;

use Symfony\Component\Filesystem\Exception\FileNotFoundException;

/**
 * @author François Pluchino <francois.pluchino@klipper.dev>
 */
interface ImageManipulatorInterface
{
    /**
     * Check if the file extension is supported.
     *
     * @param null|string $extension The file extension
     */
    public function supports(?string $extension): bool;

    /**
     * Create the image.
     *
     * @param string               $path   The image path
     * @param null|ConfigInterface $config The config
     *
     * @throws FileNotFoundException When the file is not found
     */
    public function create(string $path, ?ConfigInterface $config = null): ImageInterface;
}
