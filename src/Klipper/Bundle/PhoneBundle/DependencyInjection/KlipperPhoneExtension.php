<?php

/*
 * This file is part of the Klipper package.
 *
 * (c) François Pluchino <francois.pluchino@klipper.dev>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Klipper\Bundle\PhoneBundle\DependencyInjection;

use JMS\SerializerBundle\JMSSerializerBundle;
use Klipper\Bundle\MetadataBundle\KlipperMetadataBundle;
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
class KlipperPhoneExtension extends Extension
{
    /**
     * {@inheritdoc}
     *
     * @throws \Exception
     */
    public function load(array $configs, ContainerBuilder $container): void
    {
        $loader = new Loader\XmlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('phone_number.xml');

        if (class_exists(KlipperMetadataBundle::class)) {
            $loader->load('metadata_guess_constraint.xml');
        }

        if (class_exists(JMSSerializerBundle::class)) {
            $loader->load('serializer.xml');
        }

        if (class_exists(Environment::class)) {
            $loader->load('twig.xml');
        }
    }
}
