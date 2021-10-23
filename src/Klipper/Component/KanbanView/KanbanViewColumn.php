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
class KanbanViewColumn implements KanbanViewColumnInterface
{
    protected string $value;

    protected string $label;

    protected ?string $color = null;

    protected ?string $icon = null;

    /**
     * @var null|int|string
     */
    protected $id;

    public function __construct(string $value, string $label)
    {
        $this->value = $value;
        $this->label = $label;
    }

    public function getValue(): string
    {
        return $this->value;
    }

    public function getLabel(): string
    {
        return $this->label;
    }

    public function setColor(?string $color): self
    {
        $this->color = $color;

        return $this;
    }

    public function getColor(): ?string
    {
        return $this->color;
    }

    public function setIcon(?string $icon): self
    {
        $this->icon = $icon;

        return $this;
    }

    public function getIcon(): ?string
    {
        return $this->icon;
    }

    public function setId($id): self
    {
        $this->id = $id;

        return $this;
    }

    public function getId()
    {
        return $this->id ?? $this->value;
    }
}
