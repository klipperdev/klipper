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

/**
 * @author François Pluchino <francois.pluchino@klipper.dev>
 */
interface ImageInterface
{
    /**
     * Get the resource.
     *
     * @return resource
     */
    public function getResource();

    /**
     * Get the type mime.
     */
    public function getTypeMime(): string;

    /**
     * Get the size.
     */
    public function getSize(): int;

    /**
     * Gets the PHP callback associated with this Image.
     */
    public function getCallback(): ?callable;
}
