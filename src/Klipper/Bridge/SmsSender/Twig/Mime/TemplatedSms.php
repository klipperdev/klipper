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

use Klipper\Component\SmsSender\Mime\Sms;

/**
 * Templated sms.
 *
 * @author François Pluchino <francois.pluchino@klipper.dev>
 */
class TemplatedSms extends Sms
{
    /**
     * @var null|string
     */
    private $template;

    /**
     * @var array
     */
    private $context = [];

    /**
     * {@inheritdoc}
     */
    public function __serialize(): array
    {
        return [$this->template, $this->context, parent::__serialize()];
    }

    /**
     * {@inheritdoc}
     */
    public function __unserialize(array $data): void
    {
        [$this->template, $this->context, $parentData] = $data;

        parent::__unserialize($parentData);
    }

    /**
     * Set the template path.
     *
     * @param null|string $template The template path
     *
     * @return static
     */
    public function template(?string $template): self
    {
        $this->template = $template;

        return $this;
    }

    /**
     * Get the template path.
     *
     * @return null|string
     */
    public function getTemplate(): ?string
    {
        return $this->template;
    }

    /**
     * Set the template context.
     *
     * @param array $context The template context
     *
     * @return static
     */
    public function context(array $context): self
    {
        $this->context = $context;

        return $this;
    }

    /**
     * Get the template context.
     *
     * @return array
     */
    public function getContext(): array
    {
        return $this->context;
    }
}
