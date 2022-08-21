<?php

/*
 * This file is part of the Klipper package.
 *
 * (c) François Pluchino <francois.pluchino@klipper.dev>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Klipper\Component\SecurityOauth\Authenticator;

use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @author François Pluchino <francois.pluchino@klipper.dev>
 */
interface UserAuthenticatorInterface
{
    public function authenticateUserByCredentials(string $identifier, string $password): ?UserInterface;

    public function authenticateUser(string $identifier): ?UserInterface;
}
