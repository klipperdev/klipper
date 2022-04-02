<?php

/*
 * This file is part of the Klipper package.
 *
 * (c) François Pluchino <francois.pluchino@klipper.dev>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Klipper\Bundle\DoctrineMigrationsBundle\DependencyInjection;

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
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder('klipper_doctrine_migrations');

        /** @var ArrayNodeDefinition $rootNode */
        $rootNode = $treeBuilder->getRootNode();

        $rootNode
            ->useAttributeAsKey('connection')
            ->prototype('array')
            ->addDefaultsIfNotSet()
            ->children()
            ->arrayNode('schema_namespace_fix')
            ->addDefaultsIfNotSet()
            ->canBeEnabled()
            ->beforeNormalization()
            ->always()
            ->then(static function ($v) {
                if (null === $v || \is_string($v)) {
                    $v = [
                        'enabled' => true,
                        'namespace' => $v,
                    ];
                }

                return $v;
            })
            ->end()
            ->children()
            ->scalarNode('namespace')->defaultNull()->end()
            ->end()
            ->end()
            ->end()
            ->end()
        ;

        return $treeBuilder;
    }
}
