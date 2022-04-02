<?php

/*
 * This file is part of the Klipper package.
 *
 * (c) François Pluchino <francois.pluchino@klipper.dev>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Klipper\Bundle\CacheBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This is the class that validates and merges configuration from your app/config files.
 *
 * @author François Pluchino <francois.pluchino@klipper.dev>
 */
class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder('klipper_cache');

        /** @var ArrayNodeDefinition $rootNode */
        $rootNode = $treeBuilder->getRootNode();

        $rootNode
            ->children()
            ->append($this->getOverrideCacheServicesNode())
            ->end()
        ;

        return $treeBuilder;
    }

    /**
     * Get override cache services node.
     */
    protected function getOverrideCacheServicesNode(): ArrayNodeDefinition
    {
        $treeBuilder = new TreeBuilder('override_cache_services');

        /** @var ArrayNodeDefinition $node */
        $node = $treeBuilder->getRootNode();
        $node
            ->fixXmlConfig('override_cache_service')
            ->beforeNormalization()
            ->ifTrue(function ($v) {
                return \is_bool($v);
            })
            ->then(function ($v) {
                return true === $v
                        ? []
                        : ['_override_disabled'];
            })
            ->end()
            ->prototype('scalar')->end()
        ;

        return $node;
    }
}
