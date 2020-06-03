<?php

/*
 * This file is part of the Klipper package.
 *
 * (c) François Pluchino <francois.pluchino@klipper.dev>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Klipper\Component\Mailer\Mime;

use Twig\Extension\SandboxExtension;

/**
 * @author François Pluchino <francois.pluchino@klipper.dev>
 */
class SandboxTemplater implements SandboxTemplaterInterface
{
    private bool $sandboxed;

    private ?SandboxExtension $sandboxExtension;

    public function __construct(?SandboxExtension $sandboxExtension = null)
    {
        $this->sandboxExtension = $sandboxExtension;
        $this->sandboxed = null !== $sandboxExtension ? $sandboxExtension->isSandboxed() : false;
    }

    public function enableSandbox(): void
    {
        $this->sandboxed = true;

        if (null !== $this->sandboxExtension) {
            $this->sandboxExtension->enableSandbox();
        }
    }

    public function disableSandbox(): void
    {
        $this->sandboxed = false;

        if (null !== $this->sandboxExtension) {
            $this->sandboxExtension->disableSandbox();
        }
    }

    public function isSandboxed(): bool
    {
        return $this->sandboxed;
    }
}
