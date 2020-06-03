<?php

/*
 * This file is part of the Klipper package.
 *
 * (c) François Pluchino <francois.pluchino@klipper.dev>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Klipper\Component\Mailer\Transporter;

use Klipper\Component\Mailer\Exception\InvalidArgumentException;
use Klipper\Component\Mailer\Exception\TransporterException;
use Klipper\Component\SmsSender\Envelope;
use Klipper\Component\SmsSender\Exception\TransportExceptionInterface;
use Klipper\Component\SmsSender\Mime\Sms;
use Klipper\Component\SmsSender\SmsSenderInterface;
use Symfony\Component\Mime\RawMessage;

/**
 * Sms transporter.
 *
 * @author François Pluchino <francois.pluchino@klipper.dev>
 */
class SmsTransporter implements TransporterInterface
{
    private SmsSenderInterface $smsSender;

    public function __construct(SmsSenderInterface $smsSender)
    {
        $this->smsSender = $smsSender;
    }

    /**
     * @param null|mixed $envelope
     */
    public function supports(RawMessage $message, $envelope = null): bool
    {
        return $message instanceof Sms;
    }

    public function hasRequiredFrom(): bool
    {
        return $this->smsSender->hasRequiredFrom();
    }

    /**
     * @param null|mixed $envelope
     */
    public function send(RawMessage $message, $envelope = null): void
    {
        if (null !== $envelope && !$envelope instanceof Envelope) {
            throw new InvalidArgumentException(sprintf(
                'The envelope of message must be an instance of %s ("%s" given).',
                Envelope::class,
                \get_class($envelope)
            ));
        }

        try {
            /* @var Sms $message */
            $this->smsSender->send($message, $envelope);
        } catch (TransportExceptionInterface $e) {
            throw new TransporterException($e->getMessage(), 0, $e);
        }
    }
}
