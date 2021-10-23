<?php

/*
 * This file is part of the Klipper package.
 *
 * (c) François Pluchino <francois.pluchino@klipper.dev>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Klipper\Component\KanbanView;

/**
 * @author François Pluchino <francois.pluchino@klipper.dev>
 */
class KanbanViewManager implements KanbanViewManagerInterface
{
    private KanbanViewRegistryInterface $registry;

    public function __construct(KanbanViewRegistryInterface $registry)
    {
        $this->registry = $registry;
    }

    public function hasView(string $type, string $name): bool
    {
        return $this->registry->hasView($type, $name);
    }

    public function getView(string $type, string $name): KanbanViewInterface
    {
        return $this->registry->getView($type, $name);
    }
}
