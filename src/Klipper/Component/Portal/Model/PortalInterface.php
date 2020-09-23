<?php

/*
 * This file is part of the Klipper package.
 *
 * (c) François Pluchino <francois.pluchino@klipper.dev>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Klipper\Component\Portal\Model;

use Klipper\Contracts\Model\EnableInterface;
use Klipper\Contracts\Model\IdInterface;
use Klipper\Contracts\Model\LabelableInterface;
use Klipper\Contracts\Model\NameableInterface;

/**
 * Portal interface.
 *
 * @author François Pluchino <francois.pluchino@klipper.dev>
 */
interface PortalInterface extends
    IdInterface,
    NameableInterface,
    LabelableInterface,
    EnableInterface
{
    public function __toString(): string;
}
