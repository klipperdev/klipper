<?php

/*
 * This file is part of the Klipper package.
 *
 * (c) François Pluchino <francois.pluchino@klipper.dev>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Klipper\Bundle\PortalBundle\Security\Factory;

use Symfony\Bundle\SecurityBundle\DependencyInjection\Security\Factory\AuthenticatorFactoryInterface;
use Symfony\Bundle\SecurityBundle\DependencyInjection\Security\Factory\FirewallListenerFactoryInterface;
use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\Config\Definition\Builder\NodeDefinition;
use Symfony\Component\DependencyInjection\ChildDefinition;
use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 * Factory for portal context.
 *
 * @author François Pluchino <francois.pluchino@klipper.dev>
 */
class PortalContextFactory implements AuthenticatorFactoryInterface, FirewallListenerFactoryInterface
{
    public function getPriority(): int
    {
        return -50;
    }

    public function createAuthenticator(ContainerBuilder $container, string $firewallName, array $config, string $userProviderId): array
    {
        return [];
    }

    public function getKey(): string
    {
        return 'portal_context';
    }

    public function addConfiguration(NodeDefinition $builder): void
    {
        /* @var ArrayNodeDefinition $builder */
        $builder
            ->beforeNormalization()
            ->ifTrue(static function ($v) {
                return \is_bool($v) && $v;
            })
            ->then(static function ($v) {
                return ['is_portal' => $v];
            })
            ->end()
            ->children()
            ->scalarNode('route_parameter_name')->defaultValue('_portal')->end()
            ->booleanNode('is_portal')->defaultFalse()->end()
            ->end()
        ;
    }

    public function createListeners(ContainerBuilder $container, string $firewallName, array $config): array
    {
        $listenerId = 'klipper_portal.authenticator.portal_context.firewall_listener.'.$firewallName;
        $container
            ->setDefinition($listenerId, new ChildDefinition('klipper_portal.authenticator.portal_context.firewall_listener'))
            ->replaceArgument(1, $config)
        ;

        return [$listenerId];
    }
}
