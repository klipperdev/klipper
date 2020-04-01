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

use Klipper\Bundle\CacheBundle\DependencyInjection\Compiler\CachePoolPass;
use Klipper\Component\Cache\Adapter\FilesystemAdapter;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Cache\Adapter\AdapterInterface;
use Symfony\Component\Cache\Adapter\ApcuAdapter as SymfonyApcuAdapter;
use Symfony\Component\Cache\Adapter\FilesystemAdapter as SymfonyFilesystemAdapter;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;

/**
 * Cache Pool Pass Tests.
 *
 * @author François Pluchino <francois.pluchino@klipper.dev>
 *
 * @internal
 */
final class CachePoolPassTest extends TestCase
{
    /**
     * @var ContainerBuilder
     */
    protected $container;

    /**
     * @var CachePoolPass
     */
    protected $compiler;

    protected function setUp(): void
    {
        $this->container = new ContainerBuilder();
        $this->compiler = new CachePoolPass();
    }

    public function testOverrideCacheAdapterServiceClasses(): void
    {
        /** @var Definition[] $poolDefinitions */
        $poolDefinitions = [
            'cache.adapter.filesystem' => $this->createCacheDefinition(SymfonyFilesystemAdapter::class),
            'cache.adapter.apcu' => $this->createCacheDefinition(SymfonyApcuAdapter::class),
            'cache.adapter.abstract_adapter' => $this->createCacheDefinition(AdapterInterface::class),
        ];

        $this->container->addDefinitions($poolDefinitions);
        $this->container->setParameter('klipper_cache.override_cache_services', [
            'cache.adapter.filesystem',
        ]);

        $this->compiler->process($this->container);

        static::assertSame(FilesystemAdapter::class, $poolDefinitions['cache.adapter.filesystem']->getClass());
        static::assertSame(SymfonyApcuAdapter::class, $poolDefinitions['cache.adapter.apcu']->getClass());
        static::assertSame(AdapterInterface::class, $poolDefinitions['cache.adapter.abstract_adapter']->getClass());
    }

    private function createCacheDefinition($class)
    {
        $def = new Definition($class);
        $def->addTag('cache.pool');

        return $def;
    }
}
