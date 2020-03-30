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
use Klipper\Component\DefaultValue\Tests\Fixtures\Extension\UserExtension;
use Klipper\Component\DefaultValue\Tests\Fixtures\Type\UserType;

/**
 * Test for extensions which provide types and type extensions.
 *
 * @author François Pluchino <francois.pluchino@klipper.dev>
 */
class TestExtension extends AbstractExtension
{
    protected function loadTypes()
    {
        return [
            new UserType(),
        ];
    }

    protected function loadTypeExtensions()
    {
        return [
            new UserExtension(),
        ];
    }
}
