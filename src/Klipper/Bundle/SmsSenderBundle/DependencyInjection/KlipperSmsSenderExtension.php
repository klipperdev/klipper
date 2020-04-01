<?php

/*
 * This file is part of the Klipper package.
 *
 * (c) François Pluchino <francois.pluchino@klipper.dev>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Klipper\Bundle\SmsSenderBundle\DependencyInjection;

use Klipper\Bridge\SmsSender\Amazon\Transport\SnsTransportFactory;
use Klipper\Bridge\SmsSender\Twig\Mime\TemplatedSms;
use Klipper\Bridge\SmsSender\Twilio\Transport\TwilioTransportFactory;
use Klipper\Component\SmsSender\SmsSender;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

/**
 * This is the class that loads and manages your bundle configuration.
 *
 * @author François Pluchino <francois.pluchino@klipper.dev>
 */
class KlipperSmsSenderExtension extends Extension
{
    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container): void
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $loader = new Loader\XmlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));

        $this->configureSmsSender($container, $loader, $config);
    }

    /**
     * @throws
     */
    private function configureSmsSender(ContainerBuilder $container, LoaderInterface $loader, array $config): void
    {
        if (class_exists(SmsSender::class)) {
            $loader->load('sms_sender.xml');
            $loader->load('sms_sender_transports.xml');

            if (class_exists(TemplatedSms::class)) {
                $loader->load('twig.xml');
            }

            $container->getDefinition('klipper_sms_sender.default_transport')->setArgument(0, $config['dsn']);

            $classToServices = [
                SnsTransportFactory::class => 'klipper_sms_sender.transport_factory.amazon',
                TwilioTransportFactory::class => 'klipper_sms_sender.transport_factory.twilio',
            ];

            foreach ($classToServices as $class => $service) {
                if (!class_exists($class)) {
                    $container->removeDefinition($service);
                }
            }
        }
    }
}
