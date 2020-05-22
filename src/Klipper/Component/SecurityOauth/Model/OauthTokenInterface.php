<?php

/*
 * This file is part of the Klipper package.
 *
 * (c) François Pluchino <francois.pluchino@klipper.dev>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Klipper\Component\SecurityOauth\Model;

/**
 * @author François Pluchino <francois.pluchino@klipper.dev>
 */
interface OauthTokenInterface
{
    /**
     * @return static
     */
    public function setToken(?string $token);

    public function getToken(): ?string;

    /**
     * @return static
     */
    public function setExpiresAt(?\DateTimeInterface $expiresAt);

    public function getExpiresAt(): ?\DateTimeInterface;
}
