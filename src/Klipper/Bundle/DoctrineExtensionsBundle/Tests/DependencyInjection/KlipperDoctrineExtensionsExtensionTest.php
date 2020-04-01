<?php

/*
 * This file is part of the Klipper package.
 *
 * (c) François Pluchino <francois.pluchino@klipper.dev>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Klipper\Bundle\DoctrineExtensionsBundle\Tests\DependencyInjection;

use Klipper\Bundle\DoctrineExtensionsBundle\DependencyInjection\KlipperDoctrineExtensionsExtension;
use Klipper\Bundle\DoctrineExtensionsBundle\KlipperDoctrineExtensionsBundle;
use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 * Tests case for Extension.
 *
 * @author François Pluchino <francois.pluchino@klipper.dev>
 *
 * @internal
 */
final class KlipperDoctrineExtensionsExtensionTest extends TestCase
{
    public function testExtensionExist(): void
    {
        $container = $this->createContainer();

        static::assertTrue($container->hasExtension('klipper_doctrine_extensions'));
        static::assertTrue($container->hasDefinition('klipper.doctrine_extensions.orm.validator.unique'));
        static::assertTrue($container->hasDefinition('klipper_doctrine_extensions.orm.validator.doctrine_callback'));
    }

    /**
     * @throws
     */
    protected function createContainer(): ContainerBuilder
    {
        $container = new ContainerBuilder();

        $bundle = new KlipperDoctrineExtensionsBundle();
        $bundle->build($container);

        $extension = new KlipperDoctrineExtensionsExtension();
        $container->registerExtension($extension);
        $extension->load([], $container);

        $container->getCompilerPassConfig()->setOptimizationPasses([]);
        $container->getCompilerPassConfig()->setRemovingPasses([]);
        $container->getCompilerPassConfig()->setAfterRemovingPasses([]);
        $container->compile();

        return $container;
    }
}
