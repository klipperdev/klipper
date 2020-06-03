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

use Klipper\Component\DoctrineExtra\Exception\InvalidArgumentException;
use PHPUnit\Framework\TestCase;

/**
 * @author François Pluchino <francois.pluchino@klipper.dev>
 *
 * @internal
 */
final class InvalidArgumentExceptionTest extends TestCase
{
    public function testException(): void
    {
        $exception = new InvalidArgumentException('FOO');

        static::assertInstanceOf(InvalidArgumentException::class, $exception);
        static::assertSame('FOO', $exception->getMessage());
    }
}
