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

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Security\Core\Authentication\AuthenticationManagerInterface as SymfonyAuthenticationManagerInterface;
use Symfony\Component\Security\Core\Authentication\Token\AnonymousToken;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\ProviderNotFoundException;
use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;
use Symfony\Component\Security\Http\SecurityEvents;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;

/**
 * @author François Pluchino <francois.pluchino@klipper.dev>
 */
class AuthenticationManager implements AuthenticationManagerInterface
{
    protected SymfonyAuthenticationManagerInterface $authManager;

    protected TokenStorageInterface $tokenStorage;

    protected RequestStack $requestStack;

    protected ?EventDispatcherInterface $dispatcher;

    public function __construct(
        SymfonyAuthenticationManagerInterface $authManager,
        TokenStorageInterface $tokenStorage,
        RequestStack $requestStack,
        ?EventDispatcherInterface $dispatcher = null
    ) {
        $this->authManager = $authManager;
        $this->tokenStorage = $tokenStorage;
        $this->requestStack = $requestStack;
        $this->dispatcher = $dispatcher;
    }

    public function authenticate(TokenInterface $token): ?TokenInterface
    {
        $currentToken = $this->tokenStorage->getToken();

        if (null !== $currentToken && !$currentToken instanceof AnonymousToken) {
            return $currentToken;
        }

        try {
            $token = $this->authManager->authenticate($token);
            $this->tokenStorage->setToken($token);
            $this->dispatchInteractiveLogin($token);
        } catch (ProviderNotFoundException $e) {
            throw $e;
        } catch (\Throwable $e) {
            $token = null;
        }

        return $token;
    }

    public function getFirewallName(): string
    {
        $request = $this->requestStack->getCurrentRequest();
        $firewall = 'security.firewall.map.context.main';

        if ($request instanceof Request) {
            $firewall = $request->attributes->get('_firewall_context', $firewall);
        }

        return str_replace('security.firewall.map.context.', '', $firewall);
    }

    private function dispatchInteractiveLogin(TokenInterface $token): void
    {
        $request = $this->requestStack->getCurrentRequest();

        if (null !== $this->dispatcher && $request instanceof Request) {
            $loginEvent = new InteractiveLoginEvent($request, $token);
            $this->dispatcher->dispatch($loginEvent, SecurityEvents::INTERACTIVE_LOGIN);
        }
    }
}
