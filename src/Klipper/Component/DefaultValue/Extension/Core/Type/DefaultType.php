<?php

/*
 * This file is part of the Klipper package.
 *
 * (c) François Pluchino <francois.pluchino@klipper.dev>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Klipper\Component\DefaultValue\Extension\Core\Type;

use Klipper\Component\DefaultValue\AbstractSimpleType;

/**
 * @author François Pluchino <francois.pluchino@klipper.dev>
 */
class DefaultType extends AbstractSimpleType
{
    public function __construct(string $class = 'default')
    {
        parent::__construct($class);
    }

    public function getParent(): ?string
    {
        return null;
    }
}
