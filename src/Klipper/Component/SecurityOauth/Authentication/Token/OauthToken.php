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

    private string $providerKey;

    private array $scopes;

    /**
     * @param null|string|UserInterface $user
     * @param string[]                  $roles
     * @param string[]                  $scopes
     */
    public function __construct(
        string $token,
        $user,
        string $providerKey,
        array $roles = [],
        array $scopes = []
    ) {
        parent::__construct($roles);

        $this->token = $token;
        $this->providerKey = $providerKey;
        $this->scopes = $scopes;

        if (null !== $user) {
            $this->setUser($user);
        }

        parent::setAuthenticated(\count($roles) > 0);
    }

    public function __serialize(): array
    {
        return [$this->providerKey, parent::__serialize()];
    }

    public function __unserialize(array $data): void
    {
        [$this->providerKey, $parentData] = $data;

        parent::__unserialize($parentData);
    }

    public function setAuthenticated($isAuthenticated): void
    {
        if ($isAuthenticated) {
            throw new \LogicException('Cannot set this token to trusted after instantiation.');
        }

        parent::setAuthenticated(false);
    }

    public function getCredentials(): string
    {
        return $this->token;
    }

    public function getToken(): string
    {
        return $this->token;
    }

    public function getProviderKey(): string
    {
        return $this->providerKey;
    }

    /**
     * @return string[]
     */
    public function getScopes(): array
    {
        return $this->scopes;
    }
}
