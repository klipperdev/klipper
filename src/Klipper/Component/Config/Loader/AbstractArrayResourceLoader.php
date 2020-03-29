<?php

/*
 * This file is part of the Klipper package.
 *
 * (c) François Pluchino <francois.pluchino@klipper.dev>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Klipper\Component\Config\Loader;

use Klipper\Component\Config\ArrayResource;
use Klipper\Component\Config\ConfigCollectionInterface;
use Symfony\Component\Config\Loader\Loader;

/**
 * The base of array resource loader.
 *
 * @author François Pluchino <francois.pluchino@klipper.dev>
 */
abstract class AbstractArrayResourceLoader extends Loader
{
    /**
     * {@inheritdoc}
     *
     * @param ArrayResource $resource
     *
     * @return ConfigCollectionInterface
     */
    public function load($resource, $type = null)
    {
        $resources = $this->createConfigCollection();

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

    /**
     * Create the config collection.
     */
    abstract protected function createConfigCollection(): ConfigCollectionInterface;
}
