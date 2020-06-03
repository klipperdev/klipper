<?php

/*
 * This file is part of the Klipper package.
 *
 * (c) François Pluchino <francois.pluchino@klipper.dev>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Klipper\Bundle\RoutingBundle\DependencyInjection;

use Klipper\Bundle\SecurityBundle\KlipperSecurityBundle;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Twig\Environment;

/**
 * This is the class that loads and manages your bundle configuration.
 *
 * @author François Pluchino <francois.pluchino@klipper.dev>
 */
class KlipperRoutingExtension extends Extension
{
    /**
     * @throws \Exception
     */
    public function load(array $configs, ContainerBuilder $container): void
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $loader = new Loader\XmlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('routing.xml');

        if (class_exists(KlipperSecurityBundle::class)) {
            $loader->load('routing_security.xml');
        }

        if (class_exists(Environment::class)) {
            $loader->load('twig.xml');
        }

        if ($container->getParameterBag()->resolveValue($config['fake_host'])) {
            $loader->load('fake_host_routing.xml');
            $loader->load('fake_host_listener.xml');
        }

        $container->getDefinition('klipper_routing.pass_loader.controller_host_auto_config')
            ->replaceArgument(0, $config['controller_host_auto_config'])
        ;

        $container->getDefinition('klipper_routing.pass_loader.host_auto_config')
            ->replaceArgument(0, $config['host_auto_config'])
        ;
    }
}
