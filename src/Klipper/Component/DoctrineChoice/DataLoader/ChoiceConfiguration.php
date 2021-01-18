<?php

/*
 * This file is part of the Klipper package.
 *
 * (c) François Pluchino <francois.pluchino@klipper.dev>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Klipper\Component\DoctrineChoice\DataLoader;

use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * @author François Pluchino <francois.pluchino@klipper.dev>
 */
class ChoiceConfiguration implements ConfigurationInterface
{
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder('klipper_choices');
        /** @var ArrayNodeDefinition $rootNode */
        $rootNode = $treeBuilder->getRootNode();
        $rootNode
            ->requiresAtLeastOneElement()
            ->useAttributeAsKey('type', false)
            ->arrayPrototype()
            ->normalizeKeys(false)
            ->arrayPrototype()
            ->children()
            ->scalarNode('type')->cannotBeEmpty()->end()
            ->scalarNode('value')->cannotBeEmpty()->end()
            ->scalarNode('label')->cannotBeEmpty()->end()
            ->scalarNode('icon')->defaultNull()->end()
            ->scalarNode('color')->defaultNull()->end()
            ->integerNode('position')->defaultNull()->end()
            ->arrayNode('translations')
            ->useAttributeAsKey('locale')
            ->normalizeKeys(false)
            ->arrayPrototype()
            ->useAttributeAsKey('field')
            ->normalizeKeys(false)
            ->scalarPrototype()->end()
            ->end()
            ->end()
            ->end()
            ->end()
            ->end()
        ;

        return $treeBuilder;
    }
}
