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
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @author François Pluchino <francois.pluchino@klipper.dev>
 */
class RequestOauthToken extends OauthToken
{
    private ServerRequestInterface $serverRequest;

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
        parent::__construct($token, $user, $providerKey, $roles);

        $this->serverRequest = $serverRequest;
    }

    public function getServerRequest(): ServerRequestInterface
    {
        return $this->serverRequest;
    }
}
