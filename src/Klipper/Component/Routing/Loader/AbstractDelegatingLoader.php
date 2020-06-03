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
use Symfony\Component\Config\Loader\LoaderResolverInterface;

/**
 * @author François Pluchino <francois.pluchino@klipper.dev>
 */
abstract class AbstractDelegatingLoader implements LoaderInterface
{
    protected LoaderInterface $routeLoader;

    public function __construct(LoaderInterface $routeLoader)
    {
        $this->routeLoader = $routeLoader;
    }

    public function setResolver(LoaderResolverInterface $resolver): void
    {
        $this->routeLoader->setResolver($resolver);
    }

    public function getResolver(): LoaderResolverInterface
    {
        return $this->routeLoader->getResolver();
    }

    /**
     * @param mixed      $resource
     * @param null|mixed $type
     */
    public function supports($resource, string $type = null): bool
    {
        return $this->routeLoader->supports($resource, $type);
    }
}
