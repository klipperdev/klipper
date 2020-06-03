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

use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\Routing\RouteCollection;

/**
 * @author François Pluchino <francois.pluchino@klipper.dev>
 */
class PassDelegatingLoader extends AbstractDelegatingLoader
{
    /**
     * @var PassLoaderInterface[]
     */
    private $passLoaders;

    /**
     * @param LoaderInterface       $delegatingLoader The route loader
     * @param PassLoaderInterface[] $passLoaders      The route pass loaders
     */
    public function __construct(LoaderInterface $delegatingLoader, iterable $passLoaders = [])
    {
        parent::__construct($delegatingLoader);

        $this->passLoaders = $passLoaders;
    }

    /**
     * @param mixed      $resource
     * @param null|mixed $type
     */
    public function load($resource, string $type = null): RouteCollection
    {
        /** @var RouteCollection $collection */
        $collection = $this->routeLoader->load($resource, $type);

        foreach ($this->passLoaders as $loader) {
            $collection = $loader->load($collection);
        }

        return $collection;
    }
}
