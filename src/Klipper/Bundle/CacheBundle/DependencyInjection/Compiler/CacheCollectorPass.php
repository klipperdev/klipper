<?php

/*
 * This file is part of the Klipper package.
 *
 * (c) François Pluchino <francois.pluchino@klipper.dev>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Klipper\Bundle\CacheBundle\DependencyInjection\Compiler;

use Klipper\Component\Cache\Adapter\AdapterInterface;
use Klipper\Component\Cache\Adapter\TraceableAdapter;
use Klipper\Component\Cache\Adapter\TraceableTagAwareAdapter;
use Symfony\Component\Cache\Adapter\TraceableAdapter as SymfonyTraceableAdapter;
use Symfony\Component\Cache\Adapter\TraceableTagAwareAdapter as SymfonyTraceableTagAwareAdapter;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Reference;

/**
 * Replace the symfony cache adapters by the klipper cache adapters for all services
 * with the "cache.pool" tag.
 *
 * @author François Pluchino <francois.pluchino@klipper.dev>
 */
class CacheCollectorPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container): void
    {
        foreach ($container->findTaggedServiceIds('cache.pool') as $id => $attributes) {
            $def = $container->getDefinition($id);

            if ($this->isValidAdapter($def, SymfonyTraceableTagAwareAdapter::class)) {
                $this->replaceTraceableAdapter($container, $def, TraceableTagAwareAdapter::class);
            } elseif ($this->isValidAdapter($def, SymfonyTraceableAdapter::class)) {
                $this->replaceTraceableAdapter($container, $def, TraceableAdapter::class);
            }
        }
    }

    /**
     * Replace the traceable adapter class.
     *
     * @param ContainerBuilder $container    The container service
     * @param Definition       $def          The service definition of adapter
     * @param string           $adapterClass The new class name of adapter
     */
    private function replaceTraceableAdapter(ContainerBuilder $container, Definition $def, string $adapterClass): void
    {
        $args = $def->getArguments();

        if (\count($args) > 0 && $args[0] instanceof Reference) {
            $refDef = $container->getDefinition((string) $args[0]);

            if (\in_array(AdapterInterface::class, class_implements($refDef->getClass()), true)) {
                $def->setClass($adapterClass);
            }
        }
    }

    /**
     * Check if the adapter is valid.
     *
     * @param Definition $def        The service definition of adapter
     * @param string     $validClass The valid class name
     */
    private function isValidAdapter(Definition $def, string $validClass): bool
    {
        $class = $def->getClass();

        return $class === $validClass && TraceableTagAwareAdapter::class !== $class
            && TraceableAdapter::class !== $class;
    }
}
