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

use Klipper\Component\Mailer\Exception\TransporterExceptionInterface;
use Symfony\Component\Mime\RawMessage;

/**
 * Interface for the transporter.
 *
 * @author François Pluchino <francois.pluchino@klipper.dev>
 */
interface TransporterInterface
{
    /**
     * Check if the transporter supports the message.
     *
     * @param RawMessage  $message  The message
     * @param null|object $envelope The envelope
     */
    public function supports(RawMessage $message, $envelope = null): bool;

    /**
     * Check if the from is required.
     */
    public function hasRequiredFrom(): bool;

    /**
     * Send the message.
     *
     * @param RawMessage  $message  The message
     * @param null|object $envelope The envelope
     *
     * @throws TransporterExceptionInterface
     */
    public function send(RawMessage $message, $envelope = null): void;
}
