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
class KanbanView implements KanbanViewInterface
{
    protected string $type;

    protected string $name;

    /**
     * @var KanbanViewColumnInterface[]
     */
    protected array $columns = [];

    public function __construct(string $type, string $name)
    {
        $this->type = $type;
        $this->name = $name;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getColumns(): array
    {
        return $this->columns;
    }

    public function hasColumn(string $column): bool
    {
        return isset($this->columns[$column]);
    }

    public function addColumn(KanbanViewColumnInterface $column): self
    {
        $this->columns[$column->getValue()] = $column;

        return $this;
    }

    public function removeColumn(string $columnValue): self
    {
        unset($this->columns[$columnValue]);

        return $this;
    }
}
