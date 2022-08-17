<?php

/*
 * This file is part of the Klipper package.
 *
 * (c) François Pluchino <francois.pluchino@klipper.dev>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Klipper\Bundle\CacheBundle\Tests\DependencyInjection;

use Klipper\Bundle\CacheBundle\DependencyInjection\KlipperCacheExtension;
use Klipper\Bundle\CacheBundle\KlipperCacheBundle;
use PHPUnit\Framework\TestCase;
use Symfony\Bundle\FrameworkBundle\DependencyInjection\FrameworkExtension;
use Symfony\Bundle\FrameworkBundle\FrameworkBundle;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBag;

/**
 * Klipper Cache Extension Tests.
 *
 * @author François Pluchino <francois.pluchino@klipper.dev>
 *
 * @internal
 */
final class KlipperCacheExtensionTest extends TestCase
{
    public function testNoConfig(): void
    {
        $container = $this->createContainer();

        static::assertFalse($container->hasParameter('klipper_cache.override_cache_services'));
    }

    /**
     * Create container.
     *
     * @param array $configs    The configs
     * @param array $parameters The container parameters
     * @param array $services   The service definitions
     *
     * @return ContainerBuilder
     */
    protected function createContainer(array $configs = [], array $parameters = [], array $services = [])
    {
        $container = new ContainerBuilder(new ParameterBag([
            'kernel.bundles' => [
                'FrameworkBundle' => FrameworkBundle::class,
                'KlipperCacheBundle' => KlipperCacheBundle::class,
            ],
            'kernel.bundles_metadata' => [],
            'kernel.project_dir' => sys_get_temp_dir().'/klipper_cache_bundle',
            'kernel.cache_dir' => sys_get_temp_dir().'/klipper_cache_bundle',
            'kernel.debug' => true,
            'kernel.environment' => 'test',
            'kernel.name' => 'kernel',
            'kernel.root_dir' => sys_get_temp_dir().'/klipper_cache_bundle',
            'kernel.build_dir' => sys_get_temp_dir().'/klipper_cache_bundle',
            'kernel.charset' => 'UTF-8',
            'kernel.container_class' => Container::class,
        ]));

        $sfExt = new FrameworkExtension();
        $extension = new KlipperCacheExtension();

        $container->registerExtension($sfExt);
        $container->registerExtension($extension);

        foreach ($parameters as $name => $value) {
            $container->setParameter($name, $value);
        }

        foreach ($services as $id => $definition) {
            $container->setDefinition($id, $definition);
        }

        $sfExt->load([
            [
                'messenger' => [
                    'reset_on_message' => true,
                ],
            ],
        ], $container);
        $extension->load($configs, $container);

        $bundle = new KlipperCacheBundle();
        $bundle->build($container);

        $container->getCompilerPassConfig()->setOptimizationPasses([]);
        $container->getCompilerPassConfig()->setRemovingPasses([]);
        $container->getCompilerPassConfig()->setAfterRemovingPasses([]);
        $container->compile();

        return $container;
    }
}
