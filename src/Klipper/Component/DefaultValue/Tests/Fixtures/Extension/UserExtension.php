<?php

/*
 * This file is part of the Klipper package.
 *
 * (c) François Pluchino <francois.pluchino@klipper.dev>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Klipper\Component\DefaultValue\Tests\Fixtures\Extension;

use Klipper\Component\DefaultValue\AbstractTypeExtension;

class UserExtension extends AbstractTypeExtension
{
    public function getExtendedType(): string
    {
        return 'Klipper\Component\DefaultValue\Tests\Fixtures\Object\User';
    }
}
