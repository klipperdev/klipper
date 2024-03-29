<?php

/*
 * This file is part of the Klipper package.
 *
 * (c) François Pluchino <francois.pluchino@klipper.dev>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Klipper\Component\Security\Firewall;

use Klipper\Component\Security\Identity\SecurityIdentityManagerInterface;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\Security\Core\Authentication\AuthenticationTrustResolverInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

/**
 * Inject the public role in security identity manager.
 *
 * @author François Pluchino <francois.pluchino@klipper.dev>
 */
class PublicRoleFirewallListener extends AbstractRoleFirewallListener
{
    protected AuthenticationTrustResolverInterface $trustResolver;

    protected TokenStorageInterface $tokenStorage;

    /**
     * @param SecurityIdentityManagerInterface     $sidManager    The security identity manager
     * @param array                                $config        The config
     * @param AuthenticationTrustResolverInterface $trustResolver The authentication trust resolver
     * @param TokenStorageInterface                $tokenStorage  The token storage
     */
    public function __construct(
        SecurityIdentityManagerInterface $sidManager,
        array $config,
        AuthenticationTrustResolverInterface $trustResolver,
        TokenStorageInterface $tokenStorage
    ) {
        parent::__construct($sidManager, $config);

        $this->trustResolver = $trustResolver;
        $this->tokenStorage = $tokenStorage;
    }

    /**
     * Handles public authentication.
     *
     * @param RequestEvent $event A RequestEvent instance
     */
    public function __invoke(RequestEvent $event): void
    {
        if ($this->isEnabled() && $this->hasRole() && !$this->isAuthenticated()) {
            $this->sidManager->addSpecialRole($this->config['role']);
        }
    }

    /**
     * Check if the public role is present in config.
     */
    private function hasRole(): bool
    {
        return isset($this->config['role'])
            && \is_string($this->config['role'])
            && 0 === strpos($this->config['role'], 'ROLE_');
    }

    /**
     * Check if the token is an authenticated token.
     */
    private function isAuthenticated(): bool
    {
        $token = $this->tokenStorage->getToken();

        return null !== $token
            && $this->trustResolver->isAuthenticated($token);
    }
}
