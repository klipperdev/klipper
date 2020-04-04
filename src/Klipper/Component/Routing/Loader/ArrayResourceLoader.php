<?php

/*
 * This file is part of the Klipper package.
 *
 * (c) François Pluchino <francois.pluchino@klipper.dev>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Klipper\Component\Routing\Loader;

use Klipper\Component\Config\ArrayResource;
use Symfony\Component\Config\Loader\Loader;
use Symfony\Component\Routing\RouteCollection;

/**
 * @author François Pluchino <francois.pluchino@klipper.dev>
 */
class ArrayResourceLoader extends Loader
{
    /**
     * {@inheritdoc}
     *
     * @param ArrayResource $resource
     */
    public function load($resource, $type = null): RouteCollection
    {
        $resources = new RouteCollection();

        foreach ($resource->all() as $config) {
            $resources->addCollection($this->import($config->getResource(), $config->getType()));
        }

        return $resources;
    }

    /**
     * {@inheritdoc}
     */
    public function supports($resource, $type = null): bool
    {
        return \is_object($resource) && $resource instanceof ArrayResource;
    }
}
