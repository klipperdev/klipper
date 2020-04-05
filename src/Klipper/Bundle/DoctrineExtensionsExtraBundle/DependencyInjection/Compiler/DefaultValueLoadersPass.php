<?php

/*
 * This file is part of the Klipper package.
 *
 * (c) François Pluchino <francois.pluchino@klipper.dev>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Klipper\Bundle\DoctrineExtensionsExtraBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\Compiler\PriorityTaggedServiceTrait;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

/**
 * Register the loaders of default value.
 *
 * @author François Pluchino <francois.pluchino@klipper.dev>
 */
class DefaultValueLoadersPass implements CompilerPassInterface
{
    use PriorityTaggedServiceTrait;

    /**
     * {@inheritdoc}
     */
    public function process(ContainerBuilder $container): void
    {
        if ($container->hasDefinition('klipper_doctrine_extensions_extra.default_value.type_extension.default')) {
            $def = $container->getDefinition('klipper_doctrine_extensions_extra.default_value.type_extension.default');

            $loaders = $this->findTags($container, 'klipper_doctrine_extensions_extra.default_value.loader', $def->getArgument(0));
            $def->replaceArgument(0, $loaders);
        }
    }

    /**
     * Find and returns the services with the tag.
     *
     * @param ContainerBuilder $container The container service
     * @param string           $tag       The tag name
     * @param Reference[]      $list      The list of services
     *
     * @return Reference[]
     */
    protected function findTags(ContainerBuilder $container, string $tag, array $list): array
    {
        foreach ($this->findAndSortTaggedServices($tag, $container) as $service) {
            $list[] = $service;
        }

        return $list;
    }
}
