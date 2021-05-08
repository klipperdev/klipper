<?php

/*
 * This file is part of the Klipper package.
 *
 * (c) François Pluchino <francois.pluchino@klipper.dev>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Klipper\Bundle\PdfGeneratorBundle\DependencyInjection;

use Klipper\Bundle\SecurityBundle\DependencyInjection\NodeUtils;
use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\Config\Definition\Builder\NodeDefinition;
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
        $treeBuilder = new TreeBuilder('klipper_pdf_generator');
        /** @var ArrayNodeDefinition $rootNode */
        $rootNode = $treeBuilder->getRootNode();
        $rootNode
            ->addDefaultsIfNotSet()
            ->children()
            ->append($this->getPdfNode())
            ->append($this->getGeneratorNode())
            ->end()
        ;

        return $treeBuilder;
    }

    private function getPdfNode(): NodeDefinition
    {
        return NodeUtils::createArrayNode('pdf')
            ->addDefaultsIfNotSet()
            ->children()
            ->scalarNode('temp_dir')->defaultValue('%kernel.project_dir%/var/pdf-generator')->end()
            ->end()
        ;
    }

    private function getGeneratorNode(): NodeDefinition
    {
        return NodeUtils::createArrayNode('generator')
            ->addDefaultsIfNotSet()
            ->children()
            ->append($this->getChromeGeneratorNode())
            ->end()
        ;
    }

    private function getChromeGeneratorNode(): NodeDefinition
    {
        return NodeUtils::createArrayNode('google_chrome')
            ->addDefaultsIfNotSet()
            ->children()
            ->scalarNode('bin_path')->defaultValue('%env(KLIPPER_PDF_GENERATOR_CHROME_BIN)%')->end()
            ->scalarNode('temp_dir')->defaultValue('%kernel.project_dir%/var/pdf-generator')->end()
            ->arrayNode('default_options')
            ->defaultValue([])
            ->prototype('scalar')->end()
            ->end()
            ->end()
        ;
    }
}
