<?php

/*
 * This file is part of the Klipper package.
 *
 * (c) François Pluchino <francois.pluchino@klipper.dev>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Klipper\Component\DefaultValue\Tests;

use Klipper\Component\DefaultValue\PreloadedExtension;
use Klipper\Component\DefaultValue\Tests\Fixtures\Extension\UserExtension;
use Klipper\Component\DefaultValue\Tests\Fixtures\Type\UserType;

/**
 * @author François Pluchino <francois.pluchino@klipper.dev>
 *
 * @internal
 */
final class PreloadedExtensionTest extends AbstractBaseExtensionTest
{
    protected function setUp(): void
    {
        $types = [
            'Klipper\Component\DefaultValue\Tests\Fixtures\Object\User' => new UserType(),
        ];
        $extensions = [
            'Klipper\Component\DefaultValue\Tests\Fixtures\Object\User' => [new UserExtension()],
        ];

        $this->extension = new PreloadedExtension($types, $extensions);
    }
}
