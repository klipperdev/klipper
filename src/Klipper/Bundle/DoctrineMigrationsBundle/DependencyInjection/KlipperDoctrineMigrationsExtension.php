<?php

/*
 * This file is part of the Klipper package.
 *
 * (c) François Pluchino <francois.pluchino@klipper.dev>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Klipper\Bundle\DoctrineMigrationsBundle\DependencyInjection;

use Klipper\Bundle\DoctrineMigrationsBundle\Doctrine\Listener\SchemaNamespaceFixListener;
use Klipper\Bundle\DoctrineMigrationsBundle\Listener\CommandMigrationSubscriber;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\HttpKernel\DependencyInjection\ConfigurableExtension;

/**
 * This is the class that loads and manages your bundle configuration.
 *
 * @author François Pluchino <francois.pluchino@klipper.dev>
 */
class KlipperDoctrineMigrationsExtension extends ConfigurableExtension
{
    /**
     * {@inheritdoc}
     */
    protected function loadInternal(array $mergedConfig, ContainerBuilder $container): void
    {
        $listeners = [];

        foreach ($mergedConfig as $connection => $config) {
            if ($config['schema_namespace_fix']['enabled']) {
                $name = sprintf('klipper_doctrine_migrations.%s.schema_namespace_fix_listener', $connection);
                $definition = (new Definition())
                    ->setClass(SchemaNamespaceFixListener::class)
                    ->setPublic(false)
                    ->addArgument($config['schema_namespace_fix']['namespace'])
                    ->addTag('doctrine.event_listener', [
                        'event' => 'postGenerateSchema',
                        'connection' => $connection,
                    ])
                ;

                $container->setDefinition($name, $definition);
                $listeners[] = new Reference($name);
            }
        }

        if (!empty($listeners)) {
            $commandListenerDef = (new Definition())
                ->setClass(CommandMigrationSubscriber::class)
                ->setPublic(false)
                ->addArgument($listeners)
                ->addTag('kernel.event_subscriber')
            ;

            $container->setDefinition('klipper_doctrine_migrations.command_migration_subscriber', $commandListenerDef);
        }
    }
}
