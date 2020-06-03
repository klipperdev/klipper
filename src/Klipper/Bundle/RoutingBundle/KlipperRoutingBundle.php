<?php

/*
 * This file is part of the Klipper package.
 *
 * (c) François Pluchino <francois.pluchino@klipper.dev>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Klipper\Bundle\RoutingBundle;

use Klipper\Bundle\RoutingBundle\DependencyInjection\Compiler\RouteMainResourcePass;
use Klipper\Bundle\RoutingBundle\DependencyInjection\Compiler\RoutingPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

/**
 * @author François Pluchino <francois.pluchino@klipper.dev>
 */
class KlipperRoutingBundle extends Bundle
{
    public function build(ContainerBuilder $container): void
    {
        parent::build($container);
        $container->addCompilerPass(new RouteMainResourcePass());
        $container->addCompilerPass(new RoutingPass());
    }
}
