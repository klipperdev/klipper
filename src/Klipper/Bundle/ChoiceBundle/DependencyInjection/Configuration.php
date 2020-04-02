<?php

/*
 * This file is part of the Klipper package.
 *
 * (c) François Pluchino <francois.pluchino@klipper.dev>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Klipper\Bundle\ChoiceBundle\DependencyInjection;

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
        $treeBuilder = new TreeBuilder('klipper_choice');
        /** @var ArrayNodeDefinition $rootNode */
        $rootNode = $treeBuilder->getRootNode();
        $rootNode
            ->addDefaultsIfNotSet()
            ->children()
            ->append($this->getExtendableChoicesNode())
            ->end()
        ;

        return $treeBuilder;
    }

    /**
     * @return ArrayNodeDefinition
     */
    protected function getExtendableChoicesNode(): ArrayNodeDefinition
    {
        $treeBuilder = new TreeBuilder('extendable_choices');
        /** @var ArrayNodeDefinition $node */
        $node = $treeBuilder->getRootNode();
        $node
            ->useAttributeAsKey('name')
            ->normalizeKeys(false)
            ->prototype('array')
            ->beforeNormalization()
            ->ifTrue(static function ($value) {
                return !\array_key_exists('identifiers', $value)
                            && !\array_key_exists('override', $value)
                            && !\array_key_exists('translation_domain', $value);
            })
            ->then(static function ($value) {
                return ['identifiers' => $value];
            })
            ->end()
            ->children()
            ->scalarNode('translation_domain')->defaultNull()->end()
            ->scalarNode('override')->defaultFalse()->end()
            ->arrayNode('identifiers')
            ->isRequired()
            ->requiresAtLeastOneElement()
            ->useAttributeAsKey('name')
            ->prototype('scalar')->end()
            ->end()
            ->end()
            ->end()
        ;

        return $node;
    }
}
