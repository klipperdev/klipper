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
 * Expirable interface.
 *
 * @author François Pluchino <francois.pluchino@klipper.dev>
 */
interface ExpirableInterface
{
    /**
     * @return static
     */
    public function setExpiresAt(?\DateTimeInterface $expiresAt);

    public function getExpiresAt(): ?\DateTimeInterface;
}
