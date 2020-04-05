<?php

/*
 * This file is part of the Klipper package.
 *
 * (c) François Pluchino <francois.pluchino@klipper.dev>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Klipper\Bundle\DoctrineExtensionsExtraBundle\DependencyInjection\Compiler;

use Klipper\Bundle\DoctrineExtensionsExtraBundle\DependencyInjection\KlipperDoctrineExtensionsExtension;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 * Validate the configuration.
 *
 * @author François Pluchino <francois.pluchino@klipper.dev>
 */
class ValidateExtensionConfigurationPass implements CompilerPassInterface
{
    /**
     * Validate the DoctrineExtensions DIC extension config.
     *
     * This validation runs in a discrete compiler pass because it depends on
     * DBAL and ORM services, which aren't available during the config merge
     * compiler pass.
     */
    public function process(ContainerBuilder $container): void
    {
        /** @var KlipperDoctrineExtensionsExtension $ext */
        $ext = $container->getExtension('klipper_doctrine_extensions_extra');
        $ext->configValidate($container);
    }
}
