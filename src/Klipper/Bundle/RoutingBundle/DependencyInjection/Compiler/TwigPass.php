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
use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 * @author François Pluchino <francois.pluchino@klipper.dev>
 */
class TwigPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container): void
    {
        $twigTranslatableId = 'klipper_routing.twig.extension.translatable_routing';
        $twigOrganizationalId = 'klipper_routing.twig.extension.organizational_routing';
        $translatableId = 'klipper_routing.translatable_routing';
        $organizationalId = 'klipper_routing.organizational_routing';

        if ($container->has($twigOrganizationalId)) {
            if ($container->has($organizationalId)) {
                $container->removeDefinition($twigTranslatableId);
            } else {
                $container->removeDefinition($twigOrganizationalId);
            }
        }

        if (!$container->has($translatableId) && $container->has($twigTranslatableId)) {
            $container->removeDefinition($twigTranslatableId);
        }
    }
}
