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

use Klipper\Component\DefaultValue\Objects;
use PHPUnit\Framework\TestCase;

/**
 * @author François Pluchino <francois.pluchino@klipper.dev>
 *
 * @internal
 */
final class ObjectsTest extends TestCase
{
    public function testObjectFactoryBuilderCreator(): void
    {
        $of = Objects::createObjectFactoryBuilder();

        static::assertInstanceOf('Klipper\Component\DefaultValue\ObjectFactoryBuilderInterface', $of);
    }

    public function testObjectFactoryCreator(): void
    {
        $of = Objects::createObjectFactory();

        static::assertInstanceOf('Klipper\Component\DefaultValue\ObjectFactoryInterface', $of);
    }
}
