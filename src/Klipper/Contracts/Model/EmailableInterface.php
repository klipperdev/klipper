<?php

/*
 * This file is part of the Klipper package.
 *
 * (c) François Pluchino <francois.pluchino@klipper.dev>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Klipper\Contracts\Model;

/**
 * Interface of email model.
 *
 * @author François Pluchino <francois.pluchino@klipper.dev>
 */
interface EmailableInterface
{
    /**
     * Returns the email.
     */
    public function getEmail(): ?string;

    /**
     * Sets the email.
     *
     * @return static
     */
    public function setEmail(string $email);
}
