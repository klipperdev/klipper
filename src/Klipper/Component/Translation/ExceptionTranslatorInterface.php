<?php

/*
 * This file is part of the Klipper package.
 *
 * (c) François Pluchino <francois.pluchino@klipper.dev>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Klipper\Component\Translation;

/**
 * Exception translator interface.
 *
 * @author François Pluchino <francois.pluchino@klipper.dev>
 */
interface ExceptionTranslatorInterface
{
    /**
     * Translate the status text if available.
     *
     * @param string $message The exception message
     */
    public function trans(string $message): string;

    /**
     * Translate the exception message.
     *
     * @param string $message The exception message
     * @param array  $params  The parameters for the translator
     */
    public function transMessage(string $message, array $params = []): string;

    /**
     * Translate the message of resource domain exception.
     *
     * @param \Throwable $throwable The exception
     */
    public function transDomainThrowable(\Throwable $throwable): string;
}
