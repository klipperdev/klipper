<?php

/*
 * This file is part of the Klipper package.
 *
 * (c) François Pluchino <francois.pluchino@klipper.dev>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Klipper\Bundle\RoutingBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This is the class that validates and merges configuration from your config files.
 *
 * @author François Pluchino <francois.pluchino@klipper.dev>
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder('klipper_routing');
        /** @var ArrayNodeDefinition $rootNode */
        $rootNode = $treeBuilder->getRootNode();
        $rootNode
            ->addDefaultsIfNotSet()
            ->children()
            ->booleanNode('fake_host')->defaultValue('%kernel.debug%')->end()
            ->arrayNode('controller_host_auto_config')
            ->useAttributeAsKey('name', false)
            ->normalizeKeys(false)
            ->prototype('scalar')->end()
            ->end()
            ->arrayNode('host_auto_config')
            ->useAttributeAsKey('name', false)
            ->normalizeKeys(false)
            ->prototype('array')
            ->addDefaultsIfNotSet()
            ->children()
            ->arrayNode('schemes')
            ->beforeNormalization()
            ->castToArray()
            ->end()
            ->scalarPrototype()->end()
            ->end()
            ->arrayNode('defaults')
            ->useAttributeAsKey('name')
            ->normalizeKeys(false)
            ->prototype('scalar')->end()
            ->end()
            ->arrayNode('requirements')
            ->useAttributeAsKey('name')
            ->normalizeKeys(false)
            ->prototype('scalar')->end()
            ->end()
            ->end()
            ->end()
            ->end()
            ->end()
        ;

        return $treeBuilder;
    }
}
