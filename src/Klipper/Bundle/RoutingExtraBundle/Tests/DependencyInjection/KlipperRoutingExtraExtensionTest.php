<?php

/*
 * This file is part of the Klipper package.
 *
 * (c) François Pluchino <francois.pluchino@klipper.dev>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Klipper\Bundle\RoutingExtraBundle\Tests\DependencyInjection;

use Klipper\Bundle\RoutingExtraBundle\DependencyInjection\KlipperRoutingExtraExtension;
use Klipper\Bundle\RoutingExtraBundle\KlipperRoutingExtraBundle;
use PHPUnit\Framework\TestCase;
use Symfony\Bundle\FrameworkBundle\DependencyInjection\FrameworkExtension;
use Symfony\Bundle\FrameworkBundle\FrameworkBundle;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBag;

/**
 * @author François Pluchino <francois.pluchino@klipper.dev>
 *
 * @internal
 */
final class KlipperRoutingExtraExtensionTest extends TestCase
{
    public function testExtensionExist(): void
    {
        $container = $this->createContainer();

        static::assertTrue($container->hasExtension('klipper_routing_extra'));
    }

    public function testExtensionLoader(): void
    {
        $container = $this->createContainer();

        static::assertTrue($container->hasDefinition('klipper_routing_extra.router_extra'));
    }

    protected function createContainer(array $configs = []): ContainerBuilder
    {
        $container = new ContainerBuilder(new ParameterBag([
            'kernel.bundles' => [
                'FrameworkBundle' => FrameworkBundle::class,
                'KlipperRoutingExtraBundle' => KlipperRoutingExtraBundle::class,
            ],
            'kernel.bundles_metadata' => [],
            'kernel.project_dir' => sys_get_temp_dir().'/klipper_routing_extra_bundle',
            'kernel.cache_dir' => sys_get_temp_dir().'/klipper_routing_extra_bundle',
            'kernel.debug' => false,
            'kernel.environment' => 'test',
            'kernel.name' => 'kernel',
            'kernel.root_dir' => sys_get_temp_dir().'/klipper_routing_extra_bundle',
            'kernel.charset' => 'UTF-8',
            'kernel.container_class' => 'TestContainer',
        ]));

        $sfExt = new FrameworkExtension();
        $extension = new KlipperRoutingExtraExtension();

        $container->registerExtension($sfExt);
        $container->registerExtension($extension);

        $sfExt->load([[
            'router' => [
                'utf8' => true,
                'resource' => '.',
            ],
        ]], $container);
        $extension->load($configs, $container);

        $bundle = new KlipperRoutingExtraBundle();
        $bundle->build($container);

        $container->getCompilerPassConfig()->setOptimizationPasses([]);
        $container->getCompilerPassConfig()->setRemovingPasses([]);
        $container->getCompilerPassConfig()->setAfterRemovingPasses([]);
        $container->compile();

        return $container;
    }
}
