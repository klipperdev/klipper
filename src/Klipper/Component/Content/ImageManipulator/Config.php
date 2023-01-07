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
class Config implements ConfigInterface
{
    protected string $mode;

    protected int $scale;

    protected ?int $width;

    protected ?int $height;

    protected ?string $extension;

    protected bool $keepOriginal;

    /**
     * @param null|string $mode         The mode defined by ConfigInterface::MODE_*
     * @param null|int    $width        The width in pixel
     * @param null|int    $height       The height in pixel
     * @param null|int    $scale        The scale
     * @param null|string $extension    The file extension
     * @param bool        $keepOriginal Check if the original file must be returned
     */
    public function __construct(
        ?string $mode = null,
        ?int $width = null,
        ?int $height = null,
        ?int $scale = null,
        ?string $extension = null,
        bool $keepOriginal = false
    ) {
        $this->setMode($mode);
        $this->setScale($scale);
        $this->setWidth($width);
        $this->setHeight($height);
        $this->setExtension($extension);
        $this->setKeepOriginal($keepOriginal);
    }

    public function setMode(?string $mode): self
    {
        $this->mode = (string) $mode;

        if (!\in_array($this->mode, [self::MODE_CONTAINER, self::MODE_COVER, self::MODE_COVER_MAX], true)) {
            $this->mode = self::MODE_CONTAINER;
        }

        return $this;
    }

    public function getMode(): string
    {
        return $this->mode;
    }

    public function setScale(?int $scale): self
    {
        if ((!\is_int($scale) && !\is_float($scale)) || 0 === $scale || null === $scale) {
            $scale = 1;
        }

        $this->scale = (int) $scale;

        return $this;
    }

    public function getScale(): int
    {
        return $this->scale;
    }

    public function setWidth(?int $width): self
    {
        $this->width = $width;

        if (!\is_int($width) || 0 === $width) {
            $this->width = null;
        }

        return $this;
    }

    public function getWidth(): ?int
    {
        return $this->width;
    }

    public function setHeight(?int $height): self
    {
        $this->height = $height;

        if (!\is_int($height) || 0 === $height) {
            $this->height = null;
        }

        return $this;
    }

    public function getHeight(): ?int
    {
        return $this->height;
    }

    public function setExtension(?string $extension): self
    {
        $this->extension = $extension;

        if (!\is_string($extension)) {
            $this->extension = null;
        }

        return $this;
    }

    public function getExtension(): ?string
    {
        return $this->extension;
    }

    public function setKeepOriginal(bool $keepOriginal)
    {
        $this->keepOriginal = $keepOriginal;

        return $this;
    }

    public function getKeepOriginal(): bool
    {
        return $this->keepOriginal;
    }
}
