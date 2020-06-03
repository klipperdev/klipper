<?php

/*
 * This file is part of the Klipper package.
 *
 * (c) François Pluchino <francois.pluchino@klipper.dev>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Klipper\Component\DoctrineExtra\Tests\Exception;

use Klipper\Component\DoctrineExtra\Exception\ObjectManagerNotFoundException;
use PHPUnit\Framework\TestCase;

/**
 * @author François Pluchino <francois.pluchino@klipper.dev>
 *
 * @internal
 */
final class ObjectManagerNotFoundExceptionTest extends TestCase
{
    public function testException(): void
    {
        $exception = ObjectManagerNotFoundException::create(\stdClass::class);

        static::assertInstanceOf(ObjectManagerNotFoundException::class, $exception);
        static::assertSame('The doctrine manager for the "stdClass" class is not found', $exception->getMessage());
    }
}
