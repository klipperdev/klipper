<?php

/*
 * This file is part of the Klipper package.
 *
 * (c) François Pluchino <francois.pluchino@klipper.dev>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Klipper\Component\Config;

/**
 * The abstract class of annotation loader.
 *
 * @author François Pluchino <francois.pluchino@klipper.dev>
 */
class ArrayResource implements \IteratorAggregate
{
    /**
     * @var ConfigResource[]
     */
    private $resources = [];

    /**
     * Constructor.
     *
     * @param ConfigResource[] $resources The config resources
     */
    public function __construct(array $resources = [])
    {
        foreach ($resources as $resource) {
            if ($resource instanceof ConfigResource) {
                $this->resources[] = $resource;
            }
        }
    }

    public function __toString(): string
    {
        return __CLASS__.'('.implode(', ', $this->resources).')';
    }

    /**
     * Add the resource.
     *
     * @param ConfigResource|mixed $resource The resource
     * @param null|string          $type     The resource type
     *
     * @return static
     */
    public function add($resource, ?string $type = null): self
    {
        $this->resources[] = $resource instanceof ConfigResource ? $resource : new ConfigResource($resource, $type);

        return $this;
    }

    /**
     * Get the config resources.
     *
     * @return ConfigResource[]
     */
    public function all(): array
    {
        return $this->resources;
    }

    public function getIterator(): \ArrayIterator
    {
        return new \ArrayIterator(array_values($this->resources));
    }
}
