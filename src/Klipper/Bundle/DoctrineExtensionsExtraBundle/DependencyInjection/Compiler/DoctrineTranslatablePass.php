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

use Klipper\Bundle\DoctrineExtensionsExtraBundle\Listener\TranslatableLocaleSubscriber;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Reference;

/**
 * Pass for the doctrine translatable extension.
 *
 * @author François Pluchino <francois.pluchino@klipper.dev>
 */
class DoctrineTranslatablePass implements CompilerPassInterface
{
    /**
     * {@inheritdoc}
     */
    public function process(ContainerBuilder $container): void
    {
        if ($container->hasDefinition('stof_doctrine_extensions.listener.translatable')) {
            $container->getDefinition('stof_doctrine_extensions.listener.translatable')
                ->setPublic(true)
            ;

            $listenerDef = new Definition(TranslatableLocaleSubscriber::class, [
                new Reference('stof_doctrine_extensions.listener.translatable'),
            ]);
            $listenerDef->addTag('kernel.event_subscriber');

            $container->setDefinition('klipper_doctrine_extensions_extra.event_subscriber.doctrine_translatable_locale', $listenerDef);
        }
    }
}
