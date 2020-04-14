<?php

/*
 * This file is part of the Klipper package.
 *
 * (c) François Pluchino <francois.pluchino@klipper.dev>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Klipper\Component\Mailer\Tests\Transporter;

use Klipper\Component\Mailer\Exception\InvalidArgumentException;
use Klipper\Component\Mailer\Exception\TransporterException;
use Klipper\Component\Mailer\Transporter\EmailTransporter;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Mailer\Envelope;
use Symfony\Component\Mailer\Exception\TransportException;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Mime\Message;
use Symfony\Component\Mime\RawMessage;

/**
 * @author François Pluchino <francois.pluchino@klipper.dev>
 *
 * @internal
 * @group bug
 */
final class EmailTransporterTest extends TestCase
{
    /**
     * @var MailerInterface|MockObject
     */
    protected $sfMailer;

    /**
     * @var EmailTransporter
     */
    protected $transporter;

    protected function setUp(): void
    {
        $this->sfMailer = $this->getMockBuilder(MailerInterface::class)->getMock();
        $this->transporter = new EmailTransporter($this->sfMailer);
    }

    protected function tearDown(): void
    {
        $this->sfMailer = null;
        $this->transporter = null;
    }

    public function testSupports(): void
    {
        static::assertTrue($this->transporter->supports(new Email()));
        static::assertFalse($this->transporter->supports(new Message()));
    }

    public function testSend(): void
    {
        $message = new RawMessage('');
        $envelope = $this->getMockBuilder(Envelope::class)->disableOriginalConstructor()->getMock();

        $this->sfMailer->expects(static::once())
            ->method('send')
            ->with($message, $envelope)
        ;

        $this->transporter->send($message, $envelope);
    }

    public function testSendWithInvalidEnvelope(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('The envelope of message must be an instance of Symfony\Component\Mailer\Envelope ("stdClass" given)');

        $this->sfMailer->expects(static::never())
            ->method('send')
        ;

        $this->transporter->send(new RawMessage(''), new \stdClass());
    }

    public function testSendWithSymfonyTransportException(): void
    {
        $this->expectException(TransporterException::class);
        $this->expectExceptionMessage('TRANSPORT MESSAGE EXCEPTION');

        $exception = new TransportException('TRANSPORT MESSAGE EXCEPTION');
        $message = new RawMessage('');
        $envelope = $this->getMockBuilder(Envelope::class)->disableOriginalConstructor()->getMock();

        $this->sfMailer->expects(static::once())
            ->method('send')
            ->with($message, $envelope)
            ->willThrowException($exception)
        ;

        $this->transporter->send($message, $envelope);
    }

    public function testHasRequiredFrom(): void
    {
        static::assertTrue($this->transporter->hasRequiredFrom());
    }
}
