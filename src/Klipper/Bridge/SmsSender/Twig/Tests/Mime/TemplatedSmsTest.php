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

use Klipper\Bridge\SmsSender\Twig\Mime\TemplatedSms;
use PHPUnit\Framework\TestCase;

/**
 * @author François Pluchino <francois.pluchino@klipper.dev>
 *
 * @internal
 */
final class TemplatedSmsTest extends TestCase
{
    public function testSetTemplate(): void
    {
        $sms = new TemplatedSms();

        static::assertNull($sms->getTemplate());

        $sms->template('template.text.twig');
        static::assertSame('template.text.twig', $sms->getTemplate());
    }

    public function testSetContext(): void
    {
        $sms = new TemplatedSms();

        static::assertSame([], $sms->getContext());

        $context = [
            'foo' => 'bar',
        ];

        $sms->context($context);
        static::assertSame($context, $sms->getContext());
    }

    public function testSerialize(): void
    {
        $template = 'template.text.twig';
        $context = [
            'foo' => 'bar',
        ];

        $sms = new TemplatedSms();
        $sms->template($template);
        $sms->context($context);

        $serializedSms = serialize($sms);
        /** @var TemplatedSms $unserialiedSms */
        $unserialiedSms = unserialize($serializedSms);

        static::assertInstanceOf(TemplatedSms::class, $unserialiedSms);
        static::assertNotSame($sms, $unserialiedSms);

        static::assertSame($template, $unserialiedSms->getTemplate());
        static::assertSame($context, $unserialiedSms->getContext());
    }
}
