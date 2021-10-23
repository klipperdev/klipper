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
interface KanbanViewInterface
{
    public function getType(): string;

    public function getName(): string;

    /**
     * @return KanbanViewColumnInterface[]
     */
    public function getColumns(): array;

    public function hasColumn(string $column): bool;

    /**
     * @return static
     */
    public function addColumn(KanbanViewColumnInterface $column);

    /**
     * @return static
     */
    public function removeColumn(string $columnValue);
}
