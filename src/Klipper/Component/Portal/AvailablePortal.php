<?php

/*
 * This file is part of the Klipper package.
 *
 * (c) François Pluchino <francois.pluchino@klipper.dev>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Klipper\Component\Portal;

/**
 * @author François Pluchino <francois.pluchino@klipper.dev>
 */
class AvailablePortal
{
    /**
     * @var int|string
     */
    private $id;

    private string $name;

    private string $label;

    /**
     * @param int|string $id
     */
    public function __construct($id, string $name, string $label)
    {
        $this->id = $id;
        $this->name = $name;
        $this->label = $label;
    }

    /**
     * @return int|string
     */
    public function getId()
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getLabel(): string
    {
        return $this->label;
    }
}
