<?php

/*
 * This file is part of the Klipper package.
 *
 * (c) François Pluchino <francois.pluchino@klipper.dev>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Klipper\Component\Portal\Security\Factory;

use Symfony\Bundle\SecurityBundle\DependencyInjection\Security\Factory\SecurityFactoryInterface;
use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\Config\Definition\Builder\NodeDefinition;
use Symfony\Component\DependencyInjection\ChildDefinition;
use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 * Provider for portal context.
 *
 * @author François Pluchino <francois.pluchino@klipper.dev>
 */
class PortalContextFactory implements SecurityFactoryInterface
{
    public function create(ContainerBuilder $container, string $id, array $config, string $userProvider, ?string $defaultEntryPoint): array
    {
        $providerId = 'klipper_portal.authentication.provider.portal_context.'.$id;
        $container
            ->setDefinition($providerId, new ChildDefinition('klipper_portal.portal_context.authentication.provider'))
        ;

        $listenerId = 'klipper_portal.authentication.listener.portal_context.'.$id;
        $container
            ->setDefinition($listenerId, new ChildDefinition('klipper_portal.portal_context.authentication.listener'))
            ->replaceArgument(1, $config)
        ;

        return [$providerId, $listenerId, $defaultEntryPoint];
    }

    public function getPosition(): string
    {
        return 'remember_me';
    }

    public function getKey(): string
    {
        return 'portal_context';
    }

    public function addConfiguration(NodeDefinition $builder)
    {
        /* @var ArrayNodeDefinition $builder */
        $builder
            ->children()
            ->scalarNode('route_parameter_name')->defaultValue('_portal')->end()
            ->end()
        ;

        return $builder;
    }
}
