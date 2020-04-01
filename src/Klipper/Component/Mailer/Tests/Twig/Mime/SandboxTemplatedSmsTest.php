<?php

/*
 * This file is part of the Klipper package.
 *
 * (c) François Pluchino <francois.pluchino@klipper.dev>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Klipper\Component\Mailer\Tests\Twig\Mime;

use Klipper\Component\Mailer\Twig\Mime\SandboxTemplatedSms;
use Klipper\Bridge\SmsSender\Twig\Mime\TemplatedSms;
use PHPUnit\Framework\TestCase;

/**
 * @author François Pluchino <francois.pluchino@klipper.dev>
 *
 * @internal
 */
final class SandboxTemplatedSmsTest extends TestCase
{
    public function testConstructor(): void
    {
        static::assertInstanceOf(TemplatedSms::class, new SandboxTemplatedSms());
    }
}
