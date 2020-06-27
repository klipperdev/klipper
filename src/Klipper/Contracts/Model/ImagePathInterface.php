<?php

/*
 * This file is part of the Klipper package.
 *
 * (c) François Pluchino <francois.pluchino@klipper.dev>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Klipper\Contracts\Model;

/**
 * Interface of add image path.
 *
 * @author François Pluchino <francois.pluchino@klipper.dev>
 */
interface ImagePathInterface
{
    /**
     * Check if the image path exist.
     */
    public function hasImage(): bool;

    /**
     * Get the extension of the image path.
     */
    public function getImageExtension(): ?string;

    /**
     * Set the image path.
     *
     * @param null|string $imagePath The image path
     *
     * @return static
     */
    public function setImagePath(?string $imagePath);

    /**
     * Get the image path.
     */
    public function getImagePath(): ?string;
}
