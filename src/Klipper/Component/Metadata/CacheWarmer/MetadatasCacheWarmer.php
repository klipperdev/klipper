<?php

/*
 * This file is part of the Klipper package.
 *
 * (c) François Pluchino <francois.pluchino@klipper.dev>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Klipper\Component\Metadata\CacheWarmer;

use Klipper\Component\Metadata\MetadataFactoryInterface;
use Psr\Container\ContainerInterface;
use Symfony\Component\HttpKernel\CacheWarmer\CacheWarmerInterface;
use Symfony\Component\HttpKernel\CacheWarmer\WarmableInterface;
use Symfony\Contracts\Service\ServiceSubscriberInterface;

/**
 * @author François Pluchino <francois.pluchino@klipper.dev>
 */
class MetadatasCacheWarmer implements CacheWarmerInterface, ServiceSubscriberInterface
{
    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * @var null|MetadataFactoryInterface
     */
    private $metadataFactory;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * @param mixed $cacheDir
     */
    public function warmUp($cacheDir): void
    {
        if (null === $this->metadataFactory) {
            $this->metadataFactory = $this->container->get('klipper_metadata.factory');
        }

        if ($this->metadataFactory instanceof WarmableInterface) {
            $this->metadataFactory->warmUp($cacheDir);
        }
    }

    public function isOptional(): bool
    {
        return true;
    }

    public static function getSubscribedServices(): array
    {
        return [
            'klipper_metadata.factory' => MetadataFactoryInterface::class,
        ];
    }
}
