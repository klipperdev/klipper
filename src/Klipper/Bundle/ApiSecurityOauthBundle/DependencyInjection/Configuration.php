<?php

/*
 * This file is part of the Klipper package.
 *
 * (c) François Pluchino <francois.pluchino@klipper.dev>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Klipper\Bundle\ApiSecurityOauthBundle\DependencyInjection;

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
    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder('klipper_api_security_oauth');
        /** @var ArrayNodeDefinition $rootNode */
        $rootNode = $treeBuilder->getRootNode();
        $rootNode
            ->addDefaultsIfNotSet()
            ->append($this->getScopeLoaderNode())
        ;

        return $treeBuilder;
    }

    private function getScopeLoaderNode(): NodeDefinition
    {
        return NodeUtils::createArrayNode('scope_loader')
            ->addDefaultsIfNotSet()
            ->children()
            ->arrayNode('metadata')
            ->canBeDisabled()
            ->end()
            ->end()
        ;
    }
}
