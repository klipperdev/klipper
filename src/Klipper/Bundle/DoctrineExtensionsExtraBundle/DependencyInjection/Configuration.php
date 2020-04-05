<?php

/*
 * This file is part of the Klipper package.
 *
 * (c) François Pluchino <francois.pluchino@klipper.dev>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Klipper\Bundle\DoctrineExtensionsExtraBundle\DependencyInjection;

use Klipper\Component\DefaultValue\ObjectTypeInterface;
use Klipper\Component\DoctrineExtensionsExtra\AutoNumberable\AutoNumberableListener;
use Klipper\Component\DoctrineExtensionsExtra\DefaultValue\DefaultValueListener;
use Klipper\Component\DoctrineExtensionsExtra\Htmlable\HtmlableListener;
use Klipper\Component\DoctrineExtensionsExtra\Metadata\MetadataListener;
use Klipper\Component\Metadata\MetadataInterface;
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
        $treeBuilder = new TreeBuilder('klipper_doctrine_extensions_extra');
        /** @var ArrayNodeDefinition $rootNode */
        $rootNode = $treeBuilder->getRootNode();
        $rootNode
            ->addDefaultsIfNotSet()
            ->children()
            ->scalarNode('locale_fallback')->defaultValue('en')->end()
            ->arrayNode('pagination')
            ->addDefaultsIfNotSet()
            ->children()
            ->integerNode('default_size')->defaultValue(20)->end()
            ->integerNode('max_size')->defaultValue(100)->end()
            ->end()
            ->end()
            ->end()
            ->append($this->getVendorNode('orm'))
            ->append($this->getVendorNode('mongodb'))
            ->append($this->getClassNode())
        ;

        return $treeBuilder;
    }

    /**
     * Get the vendor node.
     */
    private function getVendorNode(string $name): ArrayNodeDefinition
    {
        $treeBuilder = new TreeBuilder($name);
        /** @var ArrayNodeDefinition $node */
        $node = $treeBuilder->getRootNode();

        $node
            ->addDefaultsIfNotSet()
            ->children()
            ->arrayNode('listeners')
            ->useAttributeAsKey('id')
            ->prototype('array')
            ->children()
            ->scalarNode('htmlable')->defaultFalse()->end()
            ->scalarNode('auto_numberable')->defaultFalse()->end()
            ->scalarNode('default_value')->defaultValue(interface_exists(ObjectTypeInterface::class))->end()
            ->scalarNode('metadata')->defaultValue(interface_exists(MetadataInterface::class))->end()
            ->end()
            ->end()
            ->end()
            ->scalarNode('table_prefix')->defaultNull()->end()
            ->end()
        ;

        return $node;
    }

    /**
     * Get the class node.
     */
    private function getClassNode(): ArrayNodeDefinition
    {
        $treeBuilder = new TreeBuilder('class');
        /** @var ArrayNodeDefinition $node */
        $node = $treeBuilder->getRootNode();

        $node
            ->addDefaultsIfNotSet()
            ->children()
            ->scalarNode('htmlable')
            ->cannotBeEmpty()
            ->defaultValue(HtmlableListener::class)
            ->end()
            ->scalarNode('auto_numberable')
            ->cannotBeEmpty()
            ->defaultValue(AutoNumberableListener::class)
            ->end()
            ->scalarNode('default_value')
            ->cannotBeEmpty()
            ->defaultValue(DefaultValueListener::class)
            ->end()
            ->scalarNode('metadata')
            ->cannotBeEmpty()
            ->defaultValue(MetadataListener::class)
            ->end()
            ->end()
        ;

        return $node;
    }
}
