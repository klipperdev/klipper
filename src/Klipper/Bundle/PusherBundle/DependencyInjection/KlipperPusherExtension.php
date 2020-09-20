<?php

/*
 * This file is part of the Klipper package.
 *
 * (c) François Pluchino <francois.pluchino@klipper.dev>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Klipper\Bundle\PusherBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

/**
 * @author François Pluchino <francois.pluchino@klipper.dev>
 */
class KlipperPusherExtension extends Extension
{
    /**
     * @throws \Exception
     */
    public function load(array $configs, ContainerBuilder $container): void
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $loader = new Loader\XmlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));

        $this->configurePusher($container, $loader, $config);
    }

    /**
     * @throws
     */
    private function configurePusher(ContainerBuilder $container, LoaderInterface $loader, array $config): void
    {
        $loader->load('pusher.xml');
        $loader->load('command.xml');

        $pmDef = $container->getDefinition('klipper_pusher.manager');
        $pmDef->addMethodCall('setLogResourceErrors', [$config['log_resource_errors']]);
    }
}
