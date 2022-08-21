<?php

/*
 * This file is part of the Klipper package.
 *
 * (c) François Pluchino <francois.pluchino@klipper.dev>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Klipper\Component\SecurityOauth\Bridge;

use Klipper\Component\SecurityOauth\Authenticator\UserAuthenticatorInterface;
use League\OAuth2\Server\Entities\ClientEntityInterface;
use League\OAuth2\Server\Entities\UserEntityInterface;
use League\OAuth2\Server\Repositories\UserRepositoryInterface;

/**
 * @author François Pluchino <francois.pluchino@klipper.dev>
 */
class UserRepository implements UserRepositoryInterface
{
    private UserAuthenticatorInterface $userAuthenticator;

    public function __construct(UserAuthenticatorInterface $userAuthenticator)
    {
        $this->userAuthenticator = $userAuthenticator;
    }

    public function getUserEntityByUserCredentials(
        $username,
        $password,
        $grantType,
        ClientEntityInterface $clientEntity
    ): ?UserEntityInterface {
        $user = $this->userAuthenticator->authenticateUserByCredentials($username, $password);

        return null !== $user ? new User($user->getUserIdentifier()) : null;
    }
}
