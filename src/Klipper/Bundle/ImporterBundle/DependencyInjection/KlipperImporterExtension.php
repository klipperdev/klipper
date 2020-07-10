<?php

/*
 * This file is part of the Klipper package.
 *
 * (c) François Pluchino <francois.pluchino@klipper.dev>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Klipper\Bundle\ImporterBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @author François Pluchino <francois.pluchino@klipper.dev>
 */
class KlipperImporterExtension extends Extension
{
    /**
     * @throws \Exception
     */
    public function load(array $configs, ContainerBuilder $container): void
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $loader = new Loader\XmlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));

        $this->configureImporter($container, $loader, $config);
    }

    /**
     * @throws
     */
    private function configureImporter(ContainerBuilder $container, LoaderInterface $loader, array $config): void
    {
        $loader->load('importer.xml');
        $loader->load('command.xml');

        if (interface_exists(UserInterface::class)) {
            $loader->load('security_listener.xml');
        }

        $imDef = $container->getDefinition('klipper_importer.manager');
        $imDef->addMethodCall('setLogResourceErrors', [$config['log_resource_errors']]);
    }
}
