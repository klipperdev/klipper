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
 * The abstract class of annotation loader.
 *
 * @author François Pluchino <francois.pluchino@klipper.dev>
 */
interface ConfigCollectionInterface extends \IteratorAggregate, \Countable
{
    /**
     * Adds a config collection at the end of the current set by appending all
     * configs of the added collection.
     *
     * @param ConfigCollectionInterface $collection The config collection
     */
    public function addCollection(ConfigCollectionInterface $collection): void;

    /**
     * Returns an array of resources loaded to build this collection.
     *
     * @return ResourceInterface[] An array of resources
     */
    public function getResources(): array;

    /**
     * Adds a resource for this collection. If the resource already exists
     * it is not added.
     *
     * @param ResourceInterface $resource The resource instance
     */
    public function addResource(ResourceInterface $resource): void;
}
