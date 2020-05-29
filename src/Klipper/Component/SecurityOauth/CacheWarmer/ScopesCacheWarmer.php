<?php

/*
 * This file is part of the Klipper package.
 *
 * (c) François Pluchino <francois.pluchino@klipper.dev>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Klipper\Component\SecurityOauth\CacheWarmer;

use Klipper\Component\SecurityOauth\Scope\ScopeRegistryInterface;
use Psr\Container\ContainerInterface;
use Symfony\Component\HttpKernel\CacheWarmer\CacheWarmerInterface;
use Symfony\Component\HttpKernel\CacheWarmer\WarmableInterface;
use Symfony\Contracts\Service\ServiceSubscriberInterface;

/**
 * @author François Pluchino <francois.pluchino@klipper.dev>
 */
class ScopesCacheWarmer implements CacheWarmerInterface, ServiceSubscriberInterface
{
    private ContainerInterface $container;

    private ?ScopeRegistryInterface $scopeLoader = null;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function warmUp($cacheDir): void
    {
        if (null === $this->scopeLoader) {
            $this->scopeLoader = $this->container->get('klipper_security_oauth.scope.registry');
        }

        if ($this->scopeLoader instanceof WarmableInterface) {
            $this->scopeLoader->warmUp($cacheDir);
        }
    }

    public function isOptional(): bool
    {
        return true;
    }

    public static function getSubscribedServices(): array
    {
        return [
            'klipper_security_oauth.scope.registry' => ScopeRegistryInterface::class,
        ];
    }
}
