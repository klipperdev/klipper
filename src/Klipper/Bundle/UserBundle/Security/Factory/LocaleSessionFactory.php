<?php

/*
 * This file is part of the Klipper package.
 *
 * (c) François Pluchino <francois.pluchino@klipper.dev>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Klipper\Bundle\UserBundle\Security\Factory;

use Symfony\Bundle\SecurityBundle\DependencyInjection\Security\Factory\SecurityFactoryInterface;
use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\Config\Definition\Builder\NodeDefinition;
use Symfony\Component\DependencyInjection\ChildDefinition;
use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 * Factory for locale session.
 *
 * @author François Pluchino <francois.pluchino@klipper.dev>
 */
class LocaleSessionFactory implements SecurityFactoryInterface
{
    /**
     * {@inheritdoc}
     */
    public function create(ContainerBuilder $container, $id, $config, $userProvider, $defaultEntryPoint): array
    {
        $providerId = 'klipper_user.authentication.locale_session.provider.'.$id;
        $container
            ->setDefinition($providerId, new ChildDefinition('klipper_user.authentication.locale_session.provider'))
        ;

        $listenerId = 'klipper_user.authentication.locale_session.listener.'.$id;
        $container
            ->setDefinition($listenerId, new ChildDefinition('klipper_user.authentication.locale_session.listener'))
            ->replaceArgument(1, $config)
        ;

        return [$providerId, $listenerId, $defaultEntryPoint];
    }

    /**
     * {@inheritdoc}
     */
    public function getPosition(): string
    {
        return 'http';
    }

    /**
     * {@inheritdoc}
     */
    public function getKey(): string
    {
        return 'locale_session';
    }

    /**
     * {@inheritdoc}
     */
    public function addConfiguration(NodeDefinition $builder): void
    {
        /* @var ArrayNodeDefinition $builder */
        $builder
            ->canBeEnabled()
        ;
    }
}
