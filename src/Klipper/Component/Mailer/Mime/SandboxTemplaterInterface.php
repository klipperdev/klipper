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

/**
 * @author François Pluchino <francois.pluchino@klipper.dev>
 */
interface SandboxTemplaterInterface
{
    /**
     * Enable the sandbox.
     */
    public function enableSandbox(): void;

    /**
     * Disable the sandbox.
     */
    public function disableSandbox(): void;

    /**
     * Check if the sandbox is enabled.
     */
    public function isSandboxed(): bool;
}
