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

use Klipper\Bridge\SmsSender\Twig\Mime\BodyRenderer;
use Klipper\Bridge\SmsSender\Twig\Mime\TemplatedSms;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Mime\Message;
use Twig\Environment;

/**
 * @author François Pluchino <francois.pluchino@klipper.dev>
 *
 * @internal
 */
final class BodyRendererTest extends TestCase
{
    public function testRenderWithNotTemplatedSmsMessage(): void
    {
        /** @var Environment|MockObject $twig */
        $twig = $this->getMockBuilder(Environment::class)->disableOriginalConstructor()->getMock();
        $renderer = new BodyRenderer($twig);

        $twig->expects(static::never())->method('render');

        $renderer->render(new Message());
    }

    public function testRenderWithTemplatedSmsMessage(): void
    {
        /** @var Environment|MockObject $twig */
        $twig = $this->getMockBuilder(Environment::class)->disableOriginalConstructor()->getMock();
        $defaultContext = [
            'foo' => 'bar',
        ];
        $renderer = new BodyRenderer($twig, $defaultContext);

        $twig->expects(static::once())
            ->method('render')
            ->with('template.txt.twig')
            ->willReturn('RENDERED SMS TEXT')
        ;

        $message = new TemplatedSms();
        $message->template('template.txt.twig');
        static::assertNull($message->getText());

        $renderer->render($message);

        static::assertSame('RENDERED SMS TEXT', $message->getText());
    }
}
