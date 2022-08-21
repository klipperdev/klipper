<?php

/*
 * This file is part of the Klipper package.
 *
 * (c) François Pluchino <francois.pluchino@klipper.dev>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Klipper\Component\SecurityOauth\Authenticator\Passport\Credentials;

use Symfony\Component\Security\Http\Authenticator\Passport\Credentials\CredentialsInterface;

/**
 * @author François Pluchino <francois.pluchino@klipper.dev>
 */
class OauthCredentials implements CredentialsInterface
{
    private string $token;

    public function __construct(string $token)
    {
        $this->token = $token;
    }

    public function getToken(): string
    {
        return $this->token;
    }

    public function isResolved(): bool
    {
        return true;
    }
}
