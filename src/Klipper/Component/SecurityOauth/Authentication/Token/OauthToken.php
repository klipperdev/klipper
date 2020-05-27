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

use Psr\Http\Message\ServerRequestInterface;
use Symfony\Component\Security\Core\Authentication\Token\AbstractToken;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @author François Pluchino <francois.pluchino@klipper.dev>
 */
class OauthToken extends AbstractToken
{
    private ServerRequestInterface $serverRequest;

    private string $token;

    private string $providerKey;

    /**
     * @param string[] $roles
     */
    public function __construct(
        ServerRequestInterface $serverRequest,
        string $token,
        ?UserInterface $user,
        string $providerKey,
        array $roles = []
    ) {
        parent::__construct($roles);

        $this->serverRequest = $serverRequest;
        $this->token = $token;
        $this->providerKey = $providerKey;

        if (null !== $user) {
            $this->setUser($user);
        }
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

    public function getServerRequest(): ServerRequestInterface
    {
        return $this->serverRequest;
    }
}
