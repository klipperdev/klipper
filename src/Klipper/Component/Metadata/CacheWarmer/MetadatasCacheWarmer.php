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

use Klipper\Component\Metadata\Listener\ConfigurationsInterface;
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
    private ContainerInterface $container;

    private ?MetadataFactoryInterface $metadataFactory = null;

    private ?ConfigurationsInterface $subscriberConfigurations = null;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * @param mixed $cacheDir
     *
     * @throws
     */
    public function warmUp($cacheDir): void
    {
        if (null === $this->metadataFactory) {
            $this->metadataFactory = $this->container->get('klipper_metadata.factory');
        }

        if (null === $this->subscriberConfigurations) {
            $this->subscriberConfigurations = $this->container->get('klipper_metadata.subscriber.configurations');
        }

        if ($this->metadataFactory instanceof WarmableInterface) {
            $this->metadataFactory->warmUp($cacheDir);
        }

        if ($this->subscriberConfigurations instanceof WarmableInterface) {
            $this->subscriberConfigurations->warmUp($cacheDir);
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
            'klipper_metadata.subscriber.configurations' => ConfigurationsInterface::class,
        ];
    }
}
