<?php

/*
 * This file is part of the Klipper package.
 *
 * (c) François Pluchino <francois.pluchino@klipper.dev>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Klipper\Component\SecurityOauth\Scope\Loader;

use Symfony\Component\Config\Resource\ResourceInterface;

/**
 * @author François Pluchino <francois.pluchino@klipper.dev>
 */
interface ScopeLoaderInterface
{
    /**
     * Load the scopes.
     *
     * @return string[]
     */
    public function load(): array;

    /**
     * Returns an array of resources loaded to build this loader.
     *
     * @return ResourceInterface[] An array of resources
     */
    public function getResources(): array;

    /**
     * Adds a resource for this loader. If the resource already exists
     * it is not added.
     *
     * @param ResourceInterface $resource The resource instance
     */
    public function addResource(ResourceInterface $resource);
}
