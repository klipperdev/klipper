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

use League\OAuth2\Server\Entities\ClientEntityInterface;
use League\OAuth2\Server\Entities\UserEntityInterface;
use League\OAuth2\Server\Repositories\UserRepositoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Security\Core\Authentication\AuthenticationManagerInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;

/**
 * @author François Pluchino <francois.pluchino@klipper.dev>
 */
class UserRepository implements UserRepositoryInterface
{
    protected UserProviderInterface $userProvider;

    protected UserPasswordEncoderInterface $userPasswordEncoder;

    protected AuthenticationManagerInterface $authManager;

    protected TokenStorageInterface $tokenStorage;

    protected RequestStack $requestStack;

    public function __construct(
        UserProviderInterface $userProvider,
        UserPasswordEncoderInterface $userPasswordEncoder,
        AuthenticationManagerInterface $authManager,
        TokenStorageInterface $tokenStorage,
        RequestStack $requestStack
    ) {
        $this->userProvider = $userProvider;
        $this->userPasswordEncoder = $userPasswordEncoder;
        $this->authManager = $authManager;
        $this->tokenStorage = $tokenStorage;
        $this->requestStack = $requestStack;
    }

    public function getUserEntityByUserCredentials(
        $username,
        $password,
        $grantType,
        ClientEntityInterface $clientEntity
    ): ?UserEntityInterface {
        try {
            $pUser = $this->userProvider->loadUserByUsername($username);
            $user = null;

            if ($this->userPasswordEncoder->isPasswordValid($pUser, $password)) {
                $user = new User($pUser->getUsername());
                $token = $this->authManager->authenticate(new UsernamePasswordToken(
                    $username,
                    $password,
                    $this->getProviderKey(),
                    []
                ));
                $this->tokenStorage->setToken($token);
            }
        } catch (\Throwable $e) {
            var_dump('la', $e->getMessage()); //TODO remove
            $user = null;
        }

        return $user;
    }

    private function getProviderKey(): string
    {
        $request = $this->requestStack->getCurrentRequest();

        $firewall = 'security.firewall.map.context.main';

        if ($request instanceof Request) {
            $firewall = $request->attributes->get('_firewall_context', $firewall);
        }

        return str_replace('security.firewall.map.context.', '', $firewall);
    }
}
