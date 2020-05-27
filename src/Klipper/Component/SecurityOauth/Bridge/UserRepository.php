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
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;

/**
 * @author François Pluchino <francois.pluchino@klipper.dev>
 */
class UserRepository implements UserRepositoryInterface
{
    private UserProviderInterface $userProvider;

    private UserPasswordEncoderInterface $userPasswordEncoder;

    private AuthenticationManagerInterface $authManager;

    public function __construct(
        UserProviderInterface $userProvider,
        UserPasswordEncoderInterface $userPasswordEncoder,
        AuthenticationManagerInterface $authManager
    ) {
        $this->userProvider = $userProvider;
        $this->userPasswordEncoder = $userPasswordEncoder;
        $this->authManager = $authManager;
    }

    public function getUserEntityByUserCredentials(
        $username,
        $password,
        $grantType,
        ClientEntityInterface $clientEntity
    ): ?UserEntityInterface {
        $pUser = $this->userProvider->loadUserByUsername($username);
        $user = null;

        if ($this->userPasswordEncoder->isPasswordValid($pUser, $password)) {
            $token = $this->authManager->authenticate(new UsernamePasswordToken(
                $username,
                $password,
                $this->authManager->getProviderKey()
            ));
            $user = null !== $token ? new User($pUser->getUsername()) : null;
        }

        return $user;
    }
}
