<?php

/*
 * This file is part of the Klipper package.
 *
 * (c) François Pluchino <francois.pluchino@klipper.dev>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Klipper\Bundle\UserBundle;

use Klipper\Bundle\UserBundle\Security\Factory\LocaleSessionFactory;
use Symfony\Bundle\SecurityBundle\DependencyInjection\SecurityExtension;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

/**
 * @author François Pluchino <francois.pluchino@klipper.dev>
 */
class KlipperUserBundle extends Bundle
{
    public function build(ContainerBuilder $container): void
    {
        if (class_exists(SecurityExtension::class)
                && $container->hasExtension('security')) {
            /** @var SecurityExtension $extension */
            $extension = $container->getExtension('security');
            $extension->addAuthenticatorFactory(new LocaleSessionFactory());
        }
    }
}
