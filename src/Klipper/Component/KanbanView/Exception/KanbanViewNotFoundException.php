<?php

/*
 * This file is part of the Klipper package.
 *
 * (c) François Pluchino <francois.pluchino@klipper.dev>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Klipper\Component\KanbanView\Exception;

/**
 * @author François Pluchino <francois.pluchino@klipper.dev>
 */
class KanbanViewNotFoundException extends KanbanNotFoundException
{
    public function __construct(string $type, $name)
    {
        $message = sprintf('The "%s" kanban view does not exist for the "%s" type', $name, $type);

        parent::__construct($message);
    }
}
