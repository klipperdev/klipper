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
    /**
     * @var string
     */
    protected $mode;

    /**
     * @var int
     */
    protected $scale;

    /**
     * @var null|int
     */
    protected $width;

    /**
     * @var null|int
     */
    protected $height;

    /**
     * @var null|string
     */
    protected $extension;

    /**
     * @var bool
     */
    protected $keepOriginal;

    /**
     * Constructor.
     *
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

    /**
     * {@inheritdoc}
     */
    public function setMode(?string $mode): self
    {
        $this->mode = $mode;

        if (!\in_array($this->mode, [self::MODE_CONTAINER, self::MODE_COVER], true)) {
            $this->mode = self::MODE_CONTAINER;
        }

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getMode(): string
    {
        return $this->mode;
    }

    /**
     * {@inheritdoc}
     */
    public function setScale(?int $scale): self
    {
        $this->scale = $scale;

        if ((!\is_int($this->scale) && !\is_float($this->scale)) || 0 === $this->scale || null === $this->scale) {
            $this->scale = 1;
        }

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getScale(): int
    {
        return $this->scale;
    }

    /**
     * {@inheritdoc}
     */
    public function setWidth(?int $width)
    {
        $this->width = $width;

        if (!\is_int($width) || 0 === $width) {
            $this->width = null;
        }

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getWidth(): ?int
    {
        return $this->width;
    }

    /**
     * {@inheritdoc}
     */
    public function setHeight(?int $height)
    {
        $this->height = $height;

        if (!\is_int($height) || 0 === $height) {
            $this->height = null;
        }

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getHeight(): ?int
    {
        return $this->height;
    }

    /**
     * {@inheritdoc}
     */
    public function setExtension(?string $extension)
    {
        $this->extension = $extension;

        if (!\is_string($extension)) {
            $this->extension = null;
        }

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getExtension(): ?string
    {
        return $this->extension;
    }

    /**
     * {@inheritdoc}
     */
    public function setKeepOriginal(bool $keepOriginal)
    {
        $this->keepOriginal = $keepOriginal;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getKeepOriginal(): bool
    {
        return $this->keepOriginal;
    }
}
