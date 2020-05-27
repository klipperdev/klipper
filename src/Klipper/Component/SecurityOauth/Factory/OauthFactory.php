<?php

/*
 * This file is part of the Klipper package.
 *
 * (c) François Pluchino <francois.pluchino@klipper.dev>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Klipper\Component\SecurityOauth\Factory;

use Symfony\Bundle\SecurityBundle\DependencyInjection\Security\Factory\SecurityFactoryInterface;
use Symfony\Component\Config\Definition\Builder\NodeDefinition;
use Symfony\Component\DependencyInjection\ChildDefinition;
use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 * @author François Pluchino <francois.pluchino@klipper.dev>
 */
class OauthFactory implements SecurityFactoryInterface
{
    public function create(ContainerBuilder $container, $id, $config, $userProvider, $defaultEntryPoint): array
    {
        $providerId = 'klipper_security_oauth.authentication.provider.oauth.'.$id;
        $container
            ->setDefinition($providerId, new ChildDefinition('klipper_security_oauth.oauth.authentication.provider'))
            ->replaceArgument(2, $id)
        ;

        $listenerId = 'klipper_security_oauth.authentication.listener.oauth.'.$id;
        $container
            ->setDefinition($listenerId, new ChildDefinition('klipper_security_oauth.oauth.authentication.listener'))
            ->replaceArgument(2, $id)
            ->replaceArgument(3, $config)
        ;

        return [$providerId, $listenerId, $defaultEntryPoint];
    }

    public function getPosition(): string
    {
        return 'pre_auth';
    }

    public function getKey(): string
    {
        return 'oauth';
    }

    public function addConfiguration(NodeDefinition $builder): void
    {
    }
}
