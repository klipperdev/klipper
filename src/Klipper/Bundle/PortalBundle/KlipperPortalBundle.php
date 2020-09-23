<?php

/*
 * This file is part of the Klipper package.
 *
 * (c) François Pluchino <francois.pluchino@klipper.dev>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Klipper\Bundle\PortalBundle;

use Klipper\Component\Portal\Security\Factory\PortalContextFactory;
use Symfony\Bundle\SecurityBundle\DependencyInjection\SecurityExtension;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

/**
 * @author François Pluchino <francois.pluchino@klipper.dev>
 */
class KlipperPortalBundle extends Bundle
{
    public function build(ContainerBuilder $container): void
    {
        if ($container->hasExtension('security')) {
            /** @var SecurityExtension $extension */
            $extension = $container->getExtension('security');
            $extension->addSecurityListenerFactory(new PortalContextFactory());
        }
    }
}
