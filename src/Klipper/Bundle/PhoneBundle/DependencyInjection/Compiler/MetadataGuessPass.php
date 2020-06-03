<?php

/*
 * This file is part of the Klipper package.
 *
 * (c) François Pluchino <francois.pluchino@klipper.dev>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Klipper\Bundle\PhoneBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Form\Extension\Core\Type\TextType;

/**
 * @author François Pluchino <francois.pluchino@klipper.dev>
 */
class MetadataGuessPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container): void
    {
        // Doctrine Guesser
        if ($container->hasDefinition('klipper_metadata.guess.doctrine_config')) {
            $def = $container->getDefinition('klipper_metadata.guess.doctrine_config');
            $mapping = $def->getArgument(1);

            $def->replaceArgument(1, array_merge($mapping, [
                'phone' => 'string',
            ]));
        }

        // Form Guesser
        if ($container->hasDefinition('klipper_metadata.guess.form_type')) {
            $def = $container->getDefinition('klipper_metadata.guess.form_type');
            $mapping = $def->getArgument(0);

            $def->replaceArgument(0, array_merge($mapping, [
                'phone' => TextType::class,
            ]));
        }
    }
}
