<?php

/*
 * This file is part of the Klipper package.
 *
 * (c) François Pluchino <francois.pluchino@klipper.dev>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Klipper\Component\ListView\Model;

use Klipper\Component\Model\Traits\LabelableInterface;
use Klipper\Component\Model\Traits\NameableInterface;

/**
 * Interface of list view model.
 *
 * @author François Pluchino <francois.pluchino@klipper.dev>
 */
interface ListViewInterface extends NameableInterface, LabelableInterface
{
    /**
     * @return static
     */
    public function setType(?string $type);

    public function getType(): ?string;

    /**
     * @return static
     */
    public function setFilters(?array $filters);

    public function getFilters(): ?array;
}
