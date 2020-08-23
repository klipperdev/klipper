<?php

/*
 * This file is part of the Klipper package.
 *
 * (c) François Pluchino <francois.pluchino@klipper.dev>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Klipper\Component\DoctrineChoice\Model;

use Klipper\Component\Model\Traits\LabelableInterface;

/**
 * Interface of choice model.
 *
 * @author François Pluchino <francois.pluchino@klipper.dev>
 */
interface ChoiceInterface extends LabelableInterface
{
    /**
     * @return static
     */
    public function setType(?string $type);

    public function getType(): ?string;

    /**
     * @return static
     */
    public function setValue(?string $value);

    public function getValue(): ?string;

    /**
     * @return static
     */
    public function setColor(?string $color);

    public function getColor(): ?string;

    /**
     * @return static
     */
    public function setPosition(?int $position);

    public function getPosition(): ?int;
}
