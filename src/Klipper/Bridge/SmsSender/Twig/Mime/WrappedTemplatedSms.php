<?php

/*
 * This file is part of the Klipper package.
 *
 * (c) François Pluchino <francois.pluchino@klipper.dev>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Klipper\Bridge\SmsSender\Twig\Mime;

use Klipper\Component\SmsSender\Mime\Phone;

/**
 * Wrapped templated sms.
 *
 * @author François Pluchino <francois.pluchino@klipper.dev>
 */
class WrappedTemplatedSms
{
    /**
     * @var TemplatedSms
     */
    private $message;

    /**
     * Constructor.
     *
     * @param TemplatedSms $message The message
     */
    public function __construct(TemplatedSms $message)
    {
        $this->message = $message;
    }

    /**
     * Get the from phone.
     *
     * @return Phone
     */
    public function getFrom(): Phone
    {
        return $this->message->getFrom();
    }

    /**
     * Add the to phones.
     *
     * @param Phone|Phone[]|string|string[] $phones The phones
     *
     * @return static
     */
    public function addTo(...$phones): self
    {
        $this->message->addTo(...$phones);

        return $this;
    }

    /**
     * Get the to phones.
     *
     * @return Phone[]
     */
    public function getTo(): array
    {
        return $this->message->getTo();
    }
}
