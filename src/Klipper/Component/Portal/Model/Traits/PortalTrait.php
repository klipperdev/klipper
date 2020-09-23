<?php

/*
 * This file is part of the Klipper package.
 *
 * (c) François Pluchino <francois.pluchino@klipper.dev>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Klipper\Component\Portal\Model\Traits;

use Klipper\Component\Model\Traits\EnableTrait;
use Klipper\Component\Model\Traits\LabelableTrait;
use Klipper\Component\Model\Traits\NameableTrait;

/**
 * Trait for portal model.
 *
 * @author François Pluchino <francois.pluchino@klipper.dev>
 */
trait PortalTrait
{
    use NameableTrait;
    use LabelableTrait;
    use EnableTrait;

    public function __toString(): string
    {
        return $this->getName();
    }
}
