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

use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Http\Authentication\UserAuthenticatorInterface as SymfonyUserAuthenticatorInterface;
use Symfony\Component\Security\Http\Authenticator\AuthenticatorInterface;

/**
 * @author François Pluchino <francois.pluchino@klipper.dev>
 */
class UserAuthenticator implements UserAuthenticatorInterface
{
    private RequestStack $requestStack;

    private TokenStorageInterface $tokenStorage;

    private UserProviderInterface $userProvider;

    private UserPasswordHasherInterface $passwordHasher;

    private SymfonyUserAuthenticatorInterface $userAuthenticator;

    private AuthenticatorInterface $authenticator;

    public function __construct(
        RequestStack $requestStack,
        TokenStorageInterface $tokenStorage,
        UserProviderInterface $userProvider,
        UserPasswordHasherInterface $passwordHasher,
        SymfonyUserAuthenticatorInterface $userAuthenticator,
        AuthenticatorInterface $authenticator
    ) {
        $this->requestStack = $requestStack;
        $this->tokenStorage = $tokenStorage;
        $this->userProvider = $userProvider;
        $this->passwordHasher = $passwordHasher;
        $this->userAuthenticator = $userAuthenticator;
        $this->authenticator = $authenticator;
    }

    public function authenticateUserByCredentials(string $identifier, string $password): ?UserInterface
    {
        return $this->doAuthenticateUserByCredentials($identifier, $password);
    }

    public function authenticateUser(string $identifier): ?UserInterface
    {
        return $this->doAuthenticateUserByCredentials($identifier, null);
    }

    private function doAuthenticateUserByCredentials(string $identifier, ?string $password): ?UserInterface
    {
        if (null === $request = $this->requestStack->getMainRequest()) {
            return null;
        }

        try {
            $user = $this->userProvider->loadUserByIdentifier($identifier);
        } catch (\Throwable) {
            return null;
        }

        if ($user instanceof PasswordAuthenticatedUserInterface
            && null !== $password
            && !$this->passwordHasher->isPasswordValid($user, $password)
        ) {
            return null;
        }

        $this->userAuthenticator->authenticateUser(
            $user,
            $this->authenticator,
            $request
        );

        $token = $this->tokenStorage->getToken();

        return null !== $token ? $token->getUser() : null;
    }
}
