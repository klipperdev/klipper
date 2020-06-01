<?php

/*
 * This file is part of the Klipper package.
 *
 * (c) François Pluchino <francois.pluchino@klipper.dev>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Klipper\Bundle\SecurityOauthMetadataBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\Config\Loader\FileLoader;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

/**
 * This is the class that loads and manages your bundle configuration.
 *
 * @author François Pluchino <francois.pluchino@klipper.dev>
 */
class KlipperSecurityOauthMetadataExtension extends Extension
{
    /**
     * {@inheritdoc}
     *
     * @throws \Exception
     */
    public function load(array $configs, ContainerBuilder $container): void
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $loader = new Loader\XmlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));

        $this->configureScopeLoader($loader, $config['scope_loader']);
        $this->configureMetadataLoader($loader, $config['metadata']);
    }

    /**
     * @throws
     */
    private function configureScopeLoader(FileLoader $loader, array $config): void
    {
        if ($config['metadata']['enabled']) {
            $loader->load('metadata_scope_loader.xml');
        }
    }

    /**
     * @throws
     */
    private function configureMetadataLoader(FileLoader $loader, array $config): void
    {
        if ($config['guesser']['oauth_scope']['enabled']) {
            $loader->load('scope_metadata_guesser.xml');
        }
    }
}
