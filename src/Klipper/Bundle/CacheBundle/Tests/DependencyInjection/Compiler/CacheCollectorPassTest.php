<?php

/*
 * This file is part of the Klipper package.
 *
 * (c) François Pluchino <francois.pluchino@klipper.dev>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Klipper\Bundle\CacheBundle\Tests\DependencyInjection\Compiler;

use Klipper\Bundle\CacheBundle\DependencyInjection\Compiler\CacheCollectorPass;
use Klipper\Component\Cache\Adapter\FilesystemAdapter;
use Klipper\Component\Cache\Adapter\TagAwareAdapter;
use Klipper\Component\Cache\Adapter\TraceableAdapter;
use Klipper\Component\Cache\Adapter\TraceableTagAwareAdapter;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Cache\Adapter\AdapterInterface;
use Symfony\Component\Cache\Adapter\ApcuAdapter as SymfonyApcuAdapter;
use Symfony\Component\Cache\Adapter\TraceableAdapter as SymfonyTraceableAdapter;
use Symfony\Component\Cache\Adapter\TraceableTagAwareAdapter as SymfonyTraceableTagAwareAdapter;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Reference;

/**
 * Cache Collector Pass Tests.
 *
 * @author François Pluchino <francois.pluchino@klipper.dev>
 *
 * @internal
 */
final class CacheCollectorPassTest extends TestCase
{
    protected ?ContainerBuilder $container = null;

    protected ?CacheCollectorPass $compiler = null;

    protected function setUp(): void
    {
        $this->container = new ContainerBuilder();
        $this->compiler = new CacheCollectorPass();
    }

    public function testOverrideCacheAdapterServiceClasses(): void
    {
        /** @var Definition[] $poolDefinitions */
        $poolDefinitions = [
            'cache.adapter.filesystem.inner' => $this->createCacheDefinition(FilesystemAdapter::class),
            'cache.adapter.tag_adapter.inner' => $this->createCacheDefinition(TagAwareAdapter::class),
            'cache.adapter.apcu.inner' => $this->createCacheDefinition(SymfonyApcuAdapter::class),
            'cache.adapter.abstract_adapter.inner' => $this->createCacheDefinition(AdapterInterface::class),

            'cache.adapter.filesystem' => $this->createCacheDefinition(SymfonyTraceableAdapter::class, 'cache.adapter.filesystem.inner'),
            'cache.adapter.tag_adapter' => $this->createCacheDefinition(SymfonyTraceableTagAwareAdapter::class, 'cache.adapter.tag_adapter.inner'),
            'cache.adapter.apcu' => $this->createCacheDefinition(SymfonyTraceableAdapter::class, 'cache.adapter.apcu.inner'),
            'cache.adapter.abstract_adapter' => $this->createCacheDefinition(SymfonyTraceableAdapter::class, 'cache.adapter.abstract_adapter.inner'),
        ];

        $this->container->addDefinitions($poolDefinitions);

        $this->compiler->process($this->container);

        static::assertSame(TraceableAdapter::class, $poolDefinitions['cache.adapter.filesystem']->getClass());
        static::assertSame(TraceableTagAwareAdapter::class, $poolDefinitions['cache.adapter.tag_adapter']->getClass());
        static::assertSame(SymfonyTraceableAdapter::class, $poolDefinitions['cache.adapter.apcu']->getClass());
        static::assertSame(SymfonyTraceableAdapter::class, $poolDefinitions['cache.adapter.abstract_adapter']->getClass());
    }

    private function createCacheDefinition($class, $referenceId = null)
    {
        $def = new Definition($class);
        $def->addTag('cache.pool');

        if (null !== $referenceId) {
            $def->setArguments([
                new Reference($referenceId),
            ]);
        }

        return $def;
    }
}
