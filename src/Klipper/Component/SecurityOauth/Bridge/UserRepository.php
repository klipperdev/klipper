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

use Klipper\Component\SecurityOauth\Authentication\AuthenticationManagerInterface;
use League\OAuth2\Server\Entities\ClientEntityInterface;
use League\OAuth2\Server\Entities\UserEntityInterface;
use League\OAuth2\Server\Repositories\UserRepositoryInterface;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;

/**
 * @author François Pluchino <francois.pluchino@klipper.dev>
 */
class UserRepository implements UserRepositoryInterface
{
    private AuthenticationManagerInterface $authManager;

    public function __construct(AuthenticationManagerInterface $authManager)
    {
        $this->authManager = $authManager;
    }

    public function getUserEntityByUserCredentials(
        $username,
        $password,
        $grantType,
        ClientEntityInterface $clientEntity
    ): ?UserEntityInterface {
        $token = $this->authManager->authenticate(new UsernamePasswordToken(
            $username,
            $password,
            $this->authManager->getProviderKey()
        ));

        return null !== $token ? new User($username) : null;
    }
}
