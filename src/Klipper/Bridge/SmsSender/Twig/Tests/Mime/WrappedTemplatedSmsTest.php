<?php

/*
 * This file is part of the Klipper package.
 *
 * (c) François Pluchino <francois.pluchino@klipper.dev>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Klipper\Bridge\SmsSender\Twig\Tests\Mime;

use Klipper\Component\SmsSender\Mime\Phone;
use Klipper\Bridge\SmsSender\Twig\Mime\TemplatedSms;
use Klipper\Bridge\SmsSender\Twig\Mime\WrappedTemplatedSms;
use PHPUnit\Framework\TestCase;

/**
 * @author François Pluchino <francois.pluchino@klipper.dev>
 *
 * @internal
 */
final class WrappedTemplatedSmsTest extends TestCase
{
    public function testGetFrom(): void
    {
        $from = new Phone('+100');
        $sms = new TemplatedSms();
        $sms->from($from);
        $wrapper = new WrappedTemplatedSms($sms);

        static::assertSame($from, $wrapper->getFrom());
    }

    public function testGetTo(): void
    {
        $to = new Phone('+100');
        $sms = new TemplatedSms();
        $wrapper = new WrappedTemplatedSms($sms);

        static::assertSame([], $wrapper->getTo());

        $wrapper->addTo($to);

        static::assertSame([$to], $wrapper->getTo());
        static::assertSame([$to], $sms->getTo());
    }
}
