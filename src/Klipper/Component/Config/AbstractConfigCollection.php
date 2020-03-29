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

use Symfony\Component\Config\Resource\ResourceInterface;

/**
 * Permission config collection.
 *
 * @author François Pluchino <francois.pluchino@klipper.dev>
 */
abstract class AbstractConfigCollection implements ConfigCollectionInterface
{
    /**
     * @var object[]
     */
    protected $configs = [];

    /**
     * @var array
     */
    protected $resources = [];

    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->configs = [];
    }

    /**
     * Clone the config collection.
     */
    public function __clone()
    {
        foreach ($this->configs as $name => $config) {
            $this->configs[$name] = clone $config;
        }
    }

    /**
     * Gets the current config collection as an Iterator that includes all configs.
     *
     * It implements \IteratorAggregate.
     *
     * @see all()
     *
     * @return \ArrayIterator An \ArrayIterator object for iterating over permission configs
     */
    public function getIterator(): \ArrayIterator
    {
        return new \ArrayIterator($this->configs);
    }

    /**
     * Gets the number of configs in this collection.
     *
     * @return int The number of configs
     */
    public function count(): int
    {
        return \count($this->configs);
    }

    /**
     * Returns all configs in this collection.
     *
     * @return object[] An array of configs
     */
    public function all(): iterable
    {
        return $this->configs;
    }

    /**
     * {@inheritdoc}
     */
    public function addCollection(ConfigCollectionInterface $collection): void
    {
        foreach ($collection->getResources() as $resource) {
            $this->addResource($resource);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getResources(): array
    {
        return array_values($this->resources);
    }

    /**
     * {@inheritdoc}
     */
    public function addResource(ResourceInterface $resource): void
    {
        $key = (string) $resource;

        if (!isset($this->resources[$key])) {
            $this->resources[$key] = $resource;
        }
    }
}
