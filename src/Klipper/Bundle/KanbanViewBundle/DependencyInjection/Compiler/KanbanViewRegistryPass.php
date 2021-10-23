<?php

/*
 * This file is part of the Klipper package.
 *
 * (c) François Pluchino <francois.pluchino@klipper.dev>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Klipper\Bundle\KanbanViewBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\Compiler\PriorityTaggedServiceTrait;
use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 * @author François Pluchino <francois.pluchino@klipper.dev>
 */
class KanbanViewRegistryPass implements CompilerPassInterface
{
    use PriorityTaggedServiceTrait;

    public function process(ContainerBuilder $container): void
    {
        if (!$container->hasDefinition('klipper_kanban_view.registry')) {
            return;
        }

        $def = $container->getDefinition('klipper_kanban_view.registry');

        foreach ($this->findAndSortTaggedServices('klipper_kanban_view.view', $container) as $service) {
            $def->addMethodCall('registerView', [$service]);
        }

        foreach ($this->findAndSortTaggedServices('klipper_kanban_view.loader', $container) as $service) {
            $def->addMethodCall('addLoader', [$service]);
        }
    }
}
