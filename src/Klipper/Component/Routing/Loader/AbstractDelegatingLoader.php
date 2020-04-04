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
    /**
     * @var LoaderInterface
     */
    protected $routeLoader;

    /**
     * Constructor.
     *
     * @param LoaderInterface $routeLoader The route loader
     */
    public function __construct(LoaderInterface $routeLoader)
    {
        $this->routeLoader = $routeLoader;
    }

    /**
     * {@inheritdoc}
     */
    public function setResolver(LoaderResolverInterface $resolver): void
    {
        $this->routeLoader->setResolver($resolver);
    }

    /**
     * {@inheritdoc}
     */
    public function getResolver(): LoaderResolverInterface
    {
        return $this->routeLoader->getResolver();
    }

    /**
     * {@inheritdoc}
     */
    public function supports($resource, $type = null): bool
    {
        return $this->routeLoader->supports($resource, $type);
    }
}
