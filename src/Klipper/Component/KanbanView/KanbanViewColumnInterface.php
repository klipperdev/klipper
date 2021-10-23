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
interface KanbanViewColumnInterface
{
    public function getValue(): string;

    public function getLabel(): string;

    /**
     * @return static
     */
    public function setColor(?string $color);

    public function getColor(): ?string;

    /**
     * @return static
     */
    public function setIcon(?string $icon);

    public function getIcon(): ?string;

    /**
     * @param null|int|string $id
     *
     * @return static
     */
    public function setId($id);

    /**
     * @return int|string
     */
    public function getId();
}
