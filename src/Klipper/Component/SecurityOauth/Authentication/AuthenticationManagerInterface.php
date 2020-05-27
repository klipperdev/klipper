<?php

/*
 * This file is part of the Klipper package.
 *
 * (c) François Pluchino <francois.pluchino@klipper.dev>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Klipper\Component\SecurityOauth\Authentication;

use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

/**
 * @author François Pluchino <francois.pluchino@klipper.dev>
 */
interface AuthenticationManagerInterface
{
    public function authenticate(TokenInterface $token): ?TokenInterface;

    public function getProviderKey(): string;
}
