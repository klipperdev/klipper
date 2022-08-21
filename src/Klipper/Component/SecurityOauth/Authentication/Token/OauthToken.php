<?php

/*
 * This file is part of the Klipper package.
 *
 * (c) François Pluchino <francois.pluchino@klipper.dev>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Klipper\Component\SecurityOauth\Authentication\Token;

use Symfony\Component\Security\Core\Authentication\Token\AbstractToken;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @author François Pluchino <francois.pluchino@klipper.dev>
 */
class OauthToken extends AbstractToken
{
    private string $token;

    private string $firewallName;

    private array $scopes;

    /**
     * @param string[] $roles
     * @param string[] $scopes
     */
    public function __construct(
        string $token,
        UserInterface $user,
        string $firewallName,
        array $roles = [],
        array $scopes = []
    ) {
        parent::__construct($roles);

        $this->token = $token;
        $this->firewallName = $firewallName;
        $this->scopes = $scopes;
        $this->setUser($user);
        $this->setAuthenticated(true, false);
    }

    public function __serialize(): array
    {
        return [$this->token, $this->firewallName, $this->scopes, parent::__serialize()];
    }

    public function __unserialize(array $data): void
    {
        [$this->token, $this->firewallName, $this->scopes, $parentData] = $data;

        parent::__unserialize($parentData);
    }

    public function getCredentials(): string
    {
        return $this->token;
    }

    public function getToken(): string
    {
        return $this->token;
    }

    public function getFirewallName(): string
    {
        return $this->firewallName;
    }

    /**
     * @return string[]
     */
    public function getScopes(): array
    {
        return $this->scopes;
    }
}
