<?php

/*
 * This file is part of the Klipper package.
 *
 * (c) François Pluchino <francois.pluchino@klipper.dev>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Klipper\Bundle\RoutingBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\Compiler\PriorityTaggedServiceTrait;
use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 * @author François Pluchino <francois.pluchino@klipper.dev>
 */
class RoutingPass implements CompilerPassInterface
{
    use PriorityTaggedServiceTrait;

    public function process(ContainerBuilder $container): void
    {
        $translatableId = 'klipper_routing.translatable_routing';
        $organizationalId = 'klipper_routing.organizational_routing';

        if (!$translatableId && !$organizationalId) {
            return;
        }

        if ($container->hasDefinition($organizationalId)) {
            if ($container->hasDefinition('klipper_security.organizational_context')) {
                $container->removeDefinition($translatableId);
                $container->setAlias($translatableId, $organizationalId);
                $container->setAlias('Klipper\Component\Routing\TranslatableRouting', $organizationalId);
                $container->setAlias('Klipper\Component\Routing\TranslatableRoutingInterface', $organizationalId);
            } else {
                $container->removeDefinition($organizationalId);
                $container->removeAlias('Klipper\Component\Routing\OrganizationalRouting');
                $container->removeAlias('Klipper\Component\Routing\OrganizationalRoutingInterface');
                $container->setAlias('klipper_routing', $translatableId);
            }
        }
    }
}
