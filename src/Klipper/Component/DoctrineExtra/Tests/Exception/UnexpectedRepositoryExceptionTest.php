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

use Klipper\Component\DoctrineExtra\Exception\UnexpectedRepositoryException;
use PHPUnit\Framework\TestCase;

/**
 * @author François Pluchino <francois.pluchino@klipper.dev>
 *
 * @internal
 */
final class UnexpectedRepositoryExceptionTest extends TestCase
{
    public function testException(): void
    {
        $exception = UnexpectedRepositoryException::create(\stdClass::class, 'RepoInterface');

        static::assertInstanceOf(UnexpectedRepositoryException::class, $exception);
        static::assertSame('The doctrine repository of the "stdClass" class is not an instance of the "RepoInterface"', $exception->getMessage());
    }
}
