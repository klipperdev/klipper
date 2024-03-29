<?php

/*
 * This file is part of the Klipper package.
 *
 * (c) François Pluchino <francois.pluchino@klipper.dev>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Klipper\Bundle\ImportBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @author François Pluchino <francois.pluchino@klipper.dev>
 */
class KlipperImportExtension extends Extension
{
    /**
     * @throws
     */
    public function load(array $configs, ContainerBuilder $container): void
    {
        $loader = new Loader\XmlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('doctrine_listener.xml');
        $loader->load('doctrine_delete_content_config.xml');
        $loader->load('import.xml');
        $loader->load('messenger.xml');
        $loader->load('command.xml');

        if (interface_exists(UserInterface::class)) {
            $loader->load('security_listener.xml');
        }
    }
}
