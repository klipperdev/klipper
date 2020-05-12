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
class SecurityOrganizationalRoutingPass implements CompilerPassInterface
{
    use PriorityTaggedServiceTrait;

    /**
     * {@inheritdoc}
     */
    public function process(ContainerBuilder $container): void
    {
        if ($container->hasDefinition('klipper_routing.organizational_routing')
                && !$container->hasDefinition('klipper_security.organizational_context')) {
            $container->removeDefinition('klipper_routing.organizational_routing');
            $container->removeAlias('Klipper\Component\Routing\OrganizationalRouting');
            $container->removeAlias('Klipper\Component\Routing\OrganizationalRoutingInterface');
        }
    }
}
