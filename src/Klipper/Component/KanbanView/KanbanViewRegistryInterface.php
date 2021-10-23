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

use Klipper\Component\KanbanView\Exception\KanbanViewNotFoundException;
use Klipper\Component\KanbanView\Loader\KanbanViewLoaderInterface;

/**
 * @author François Pluchino <francois.pluchino@klipper.dev>
 */
interface KanbanViewRegistryInterface
{
    public function addLoader(KanbanViewLoaderInterface $loader): void;

    /**
     * @return static
     */
    public function registerView(KanbanViewInterface $view);

    public function unregisterView(string $type, string $name);

    public function hasView(string $type, string $name): bool;

    /**
     * @throws KanbanViewNotFoundException When the kanban view is not found
     */
    public function getView(string $type, string $name): KanbanViewInterface;
}
