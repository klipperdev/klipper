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

use Klipper\Component\Routing\Router;
use Psr\Container\ContainerInterface;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

/**
 * @author François Pluchino <francois.pluchino@klipper.dev>
 */
class FakeHostRoutingPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container): void
    {
        // Remove deprecation warning of Psr\Container\ContainerInterface autowiring alias since Symfony 5.1
        // caused by the decoration of the router.default service by the fake host router
        if ($container->hasAlias(Router::class) && $container->hasDefinition('router.default')) {
            $def = $container->getDefinition('router.default');
            $arg0 = (string) $def->getArgument(0);

            if (interface_exists($arg0) && is_a($arg0, ContainerInterface::class, true)) {
                $def->setArgument(0, new Reference('service_container'));
            }
        }
    }
}
