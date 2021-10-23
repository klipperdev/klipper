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
interface KanbanViewManagerInterface
{
    public function hasView(string $type, string $name): bool;

    public function getView(string $type, string $name): KanbanViewInterface;
}
