<?php

/*
 * This file is part of the Klipper package.
 *
 * (c) François Pluchino <francois.pluchino@klipper.dev>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Klipper\Component\KanbanView\Loader;

use Klipper\Component\KanbanView\KanbanView;

/**
 * @author François Pluchino <francois.pluchino@klipper.dev>
 */
interface KanbanViewLoaderInterface
{
    public function supports(string $type, string $name): bool;

    /**
     * @return KanbanView[]
     */
    public function load(string $type, string $name): array;
}
