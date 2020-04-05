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
class Image implements ImageInterface
{
    /**
     * @var resource
     */
    protected $resource;

    /**
     * @var string
     */
    protected $typeMime;

    /**
     * @var int
     */
    protected $size;

    /**
     * @var null|callable
     */
    protected $callback;

    /**
     * Constructor.
     *
     * @param resource $resource The stream resource
     * @param string   $typeMime The type mime
     * @param callable $callback A valid PHP callback
     */
    public function __construct($resource, string $typeMime, ?callable $callback = null)
    {
        $this->resource = $resource;
        $this->typeMime = $typeMime;
        $this->size = fstat($resource)['size'];
        $this->callback = $callback;
    }

    /**
     * {@inheritdoc}
     */
    public function getResource()
    {
        return $this->resource;
    }

    /**
     * {@inheritdoc}
     */
    public function getTypeMime(): string
    {
        return $this->typeMime;
    }

    /**
     * {@inheritdoc}
     */
    public function getSize(): int
    {
        return $this->size;
    }

    /**
     * {@inheritdoc}
     */
    public function getCallback(): ?callable
    {
        return $this->callback;
    }
}
