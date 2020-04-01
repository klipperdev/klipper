<?php

/*
 * This file is part of the Klipper package.
 *
 * (c) François Pluchino <francois.pluchino@klipper.dev>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Klipper\Component\Mailer\Tests\Exception;

use Klipper\Component\Mailer\Exception\TransporterException;
use PHPUnit\Framework\TestCase;

/**
 * @author François Pluchino <francois.pluchino@klipper.dev>
 *
 * @internal
 */
final class TransporterExceptionTest extends TestCase
{
    public function testException(): void
    {
        $e = new TransporterException('MESSAGE');

        static::assertSame('MESSAGE', $e->getMessage());
    }
}
