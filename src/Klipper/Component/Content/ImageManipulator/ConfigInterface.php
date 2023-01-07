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
interface ConfigInterface
{
    public const MODE_CONTAINER = 'container';

    public const MODE_COVER = 'cover';

    public const MODE_COVER_MAX = 'cover_max';

    /**
     * Set the mode.
     *
     * @param null|string $mode The mode
     *
     * @return static
     */
    public function setMode(?string $mode);

    /**
     * Get the mode.
     */
    public function getMode(): string;

    /**
     * Set the scale.
     *
     * @param null|int $scale The scale
     *
     * @return static
     */
    public function setScale(?int $scale);

    /**
     * Get the scale.
     */
    public function getScale(): int;

    /**
     * Set the width.
     *
     * @param null|int $width The width
     *
     * @return static
     */
    public function setWidth(?int $width);

    /**
     * Get the width.
     */
    public function getWidth(): ?int;

    /**
     * Set the height.
     *
     * @param null|int $height The height
     *
     * @return static
     */
    public function setHeight(?int $height);

    /**
     * Get the height.
     */
    public function getHeight(): ?int;

    /**
     * Set the file extension.
     *
     * @param null|string $extension The file extension
     *
     * @return static
     */
    public function setExtension(?string $extension);

    /**
     * Get the file extension.
     */
    public function getExtension(): ?string;

    /**
     * Defined if the original image must be returned.
     *
     * @param bool $keepOriginal The value
     *
     * @return static
     */
    public function setKeepOriginal(bool $keepOriginal);

    /**
     * Check if the original image must be returned.
     */
    public function getKeepOriginal(): bool;
}
