<?php

/*
 * This file is part of the Klipper package.
 *
 * (c) François Pluchino <francois.pluchino@klipper.dev>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Klipper\Component\SmsSender\Event;

use Klipper\Component\SmsSender\Envelope;
use Symfony\Component\Mime\RawMessage;
use Symfony\Contracts\EventDispatcher\Event;

/**
 * @author François Pluchino <francois.pluchino@klipper.dev>
 */
abstract class AbstractMessageEvent extends Event
{
    private RawMessage $message;

    private Envelope $envelope;

    /**
     * @param RawMessage $message  The message
     * @param Envelope   $envelope The envelope
     */
    public function __construct(RawMessage $message, Envelope $envelope)
    {
        $this->message = $message;
        $this->envelope = $envelope;
    }

    /**
     * Get the message.
     */
    public function getMessage(): RawMessage
    {
        return $this->message;
    }

    /**
     * Set the message.
     *
     * @param RawMessage $message The message
     */
    public function setMessage(RawMessage $message): void
    {
        $this->message = $message;
    }

    /**
     * Get the envelope.
     */
    public function getEnvelope(): Envelope
    {
        return $this->envelope;
    }

    /**
     * Set the envelope.
     *
     * @param Envelope $envelope The envelope
     */
    public function setEnvelope(Envelope $envelope): void
    {
        $this->envelope = $envelope;
    }
}
