<?php

/*
 * This file is part of the Klipper package.
 *
 * (c) François Pluchino <francois.pluchino@klipper.dev>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Klipper\Bundle\ApiSecurityOauthBundle\DependencyInjection;

use Klipper\Bundle\MetadataBundle\KlipperMetadataBundle;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

/**
 * This is the class that loads and manages your bundle configuration.
 *
 * @author François Pluchino <francois.pluchino@klipper.dev>
 */
class KlipperApiSecurityOauthExtension extends Extension
{
    /**
     * {@inheritdoc}
     *
     * @throws \Exception
     */
    public function load(array $configs, ContainerBuilder $container): void
    {
        $ref = new \ReflectionClass($this);
        $configPath = \dirname($ref->getFileName(), 2).'/Resources/config';
        $loader = new Loader\XmlFileLoader($container, new FileLocator($configPath));

        if (class_exists(KlipperMetadataBundle::class)) {
            $loader->load('metadata_scope_loader.xml');
        }
    }
}
