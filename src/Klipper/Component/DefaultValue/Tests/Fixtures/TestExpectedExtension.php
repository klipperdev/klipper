<?php

/*
 * This file is part of the Klipper package.
 *
 * (c) François Pluchino <francois.pluchino@klipper.dev>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Klipper\Component\DefaultValue\Tests\Fixtures;

use Klipper\Component\DefaultValue\AbstractExtension;

/**
 * Test for extensions which provide types and type extensions.
 *
 * @author François Pluchino <francois.pluchino@klipper.dev>
 */
class TestExpectedExtension extends AbstractExtension
{
    protected function loadTypes(): array
    {
        return [
            'foo',
        ];
    }

    protected function loadTypeExtensions(): array
    {
        return [
            'bar',
        ];
    }
}
