<?php

/*
 * This file is part of the Klipper package.
 *
 * (c) François Pluchino <francois.pluchino@klipper.dev>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Klipper\Component\SecurityOauth\Firewall;

use Klipper\Component\HttpFoundation\Psr7\Psr7WrappedRequest;
use Klipper\Component\SecurityOauth\Authentication\Token\OauthToken;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\Security\Core\Authentication\AuthenticationManagerInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

/**
 * @author François Pluchino <francois.pluchino@klipper.dev>
 */
class OauthListener
{
    private TokenStorageInterface $tokenStorage;

    private AuthenticationManagerInterface $authenticationManager;

    private string $providerKey;

    private array $config;

    public function __construct(
        TokenStorageInterface $tokenStorage,
        AuthenticationManagerInterface $authenticationManager,
        string $providerKey,
        array $config
    ) {
        $this->tokenStorage = $tokenStorage;
        $this->authenticationManager = $authenticationManager;
        $this->providerKey = $providerKey;
        $this->config = $config;
    }

    public function __invoke(RequestEvent $event): void
    {
        $request = $event->getRequest();

        if (!$request->headers->has('Authorization')) {
            return;
        }

        $authorization = $request->headers->get('Authorization');

        if (0 !== strpos($authorization, 'Bearer ')) {
            return;
        }

        $token = $this->authenticationManager->authenticate(new OauthToken(
            new Psr7WrappedRequest($request),
            substr($authorization, 7),
            null,
            $this->providerKey
        ));
        $this->tokenStorage->setToken($token);
    }
}
