<?php

/*
 * This file is part of the Klipper package.
 *
 * (c) François Pluchino <francois.pluchino@klipper.dev>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Klipper\Bundle\PortalBundle\DependencyInjection;

use Klipper\Bundle\SecurityBundle\DependencyInjection\NodeUtils;
use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\Config\Definition\Builder\NodeDefinition;
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
        $treeBuilder = new TreeBuilder('klipper_portal');
        /** @var ArrayNodeDefinition $rootNode */
        $rootNode = $treeBuilder->getRootNode();
        $rootNode
            ->addDefaultsIfNotSet()
            ->children()
            ->append($this->getContextNode())
            ->append($this->getTwigNode())
            ->end()
        ;

        return $treeBuilder;
    }

    private function getContextNode(): NodeDefinition
    {
        return NodeUtils::createArrayNode('context')
            ->addDefaultsIfNotSet()
            ->children()
            ->arrayNode('security')
            ->addDefaultsIfNotSet()
            ->children()
            ->scalarNode('permission_name')->defaultValue('back-office')->end()
            ->append($this->getContextSecurityExpressionLanguageNode())
            ->end()
            ->end()
            ->end()
        ;
    }

    private function getContextSecurityExpressionLanguageNode(): NodeDefinition
    {
        return NodeUtils::createArrayNode('expression')
            ->addDefaultsIfNotSet()
            ->children()
            ->arrayNode('functions')
            ->addDefaultsIfNotSet()
            ->children()
            ->scalarNode('is_portal')->defaultFalse()->end()
            ->end()
            ->end()
            ->end()
        ;
    }

    private function getTwigNode(): NodeDefinition
    {
        return NodeUtils::createArrayNode('twig')
            ->addDefaultsIfNotSet()
            ->children()
            ->scalarNode('template_base_path')->defaultValue('portal/')->end()
            ->end()
        ;
    }
}
