<?php

/*
 * This file is part of the Klipper package.
 *
 * (c) François Pluchino <francois.pluchino@klipper.dev>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Klipper\Bundle\SmsSenderBundle\Tests\DependencyInjection;

use Klipper\Bundle\SmsSenderBundle\DependencyInjection\KlipperSmsSenderExtension;
use Klipper\Bundle\SmsSenderBundle\KlipperSmsSenderBundle;
use Klipper\Component\SmsSender\SmsSenderInterface;
use PHPUnit\Framework\TestCase;
use Symfony\Bundle\FrameworkBundle\DependencyInjection\FrameworkExtension;
use Symfony\Bundle\FrameworkBundle\FrameworkBundle;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBag;

/**
 * Tests for symfony extension configuration.
 *
 * @author François Pluchino <francois.pluchino@klipper.dev>
 *
 * @internal
 */
final class KlipperSmsSenderExtensionTest extends TestCase
{
    public function testExtensionExist(): void
    {
        $container = $this->createContainer();

        static::assertTrue($container->hasExtension('klipper_sms_sender'));
    }

    public function testExtensionLoader(): void
    {
        $container = $this->createContainer();

        static::assertTrue($container->hasDefinition('klipper_sms_sender.sender'));
        static::assertTrue($container->hasDefinition('klipper_sms_sender.default_transport'));
        static::assertTrue($container->hasDefinition('klipper_sms_sender.messenger.message_handler'));
        static::assertTrue($container->hasAlias('sms_sender'));
        static::assertTrue($container->hasAlias(SmsSenderInterface::class));

        static::assertTrue($container->hasDefinition('klipper_sms_sender.twig.message_listener'));
        static::assertTrue($container->hasDefinition('klipper_sms_sender.twig.mime_body_renderer'));
    }

    protected function createContainer(array $configs = [], array $parameters = []): ContainerBuilder
    {
        $container = new ContainerBuilder(new ParameterBag([
            'kernel.bundles' => [
                'FrameworkBundle' => FrameworkBundle::class,
                'KlipperSmsSenderBundle' => KlipperSmsSenderBundle::class,
            ],
            'kernel.bundles_metadata' => [],
            'kernel.cache_dir' => sys_get_temp_dir().'/klipper_sms_sender_bundle',
            'kernel.debug' => false,
            'kernel.environment' => 'test',
            'kernel.name' => 'kernel',
            'kernel.project_dir' => sys_get_temp_dir().'/klipper_sms_sender_bundle',
            'kernel.root_dir' => sys_get_temp_dir().'/klipper_sms_sender_bundle/app',
            'kernel.charset' => 'UTF-8',
            'kernel.container_class' => 'TestContainer',
        ]));

        $container->getParameterBag()->add($parameters);

        $sfExt = new FrameworkExtension();
        $extension = new KlipperSmsSenderExtension();

        $container->registerExtension($sfExt);
        $container->registerExtension($extension);

        $sfExt->load([
            [
                'messenger' => [
                    'reset_on_message' => true,
                ],
            ],
        ], $container);
        $extension->load($configs, $container);

        $bundle = new KlipperSmsSenderBundle();
        $bundle->build($container);

        $container->getCompilerPassConfig()->setOptimizationPasses([]);
        $container->getCompilerPassConfig()->setRemovingPasses([]);
        $container->getCompilerPassConfig()->setAfterRemovingPasses([]);
        $container->compile();

        return $container;
    }
}
