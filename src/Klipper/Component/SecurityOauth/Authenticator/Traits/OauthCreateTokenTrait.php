<?php

/*
 * This file is part of the Klipper package.
 *
 * (c) François Pluchino <francois.pluchino@klipper.dev>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Klipper\Component\SecurityOauth\Authenticator\Traits;

use Klipper\Component\SecurityOauth\Authentication\Token\OauthToken;
use Klipper\Component\SecurityOauth\Authenticator\Passport\Badge\OauthScopesBadge;
use Klipper\Component\SecurityOauth\Authenticator\Passport\Credentials\OauthCredentials;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;

/**
 * @author François Pluchino <francois.pluchino@klipper.dev>
 */
trait OauthCreateTokenTrait
{
    public function createToken(Passport $passport, string $firewallName): TokenInterface
    {
        $credentials = $passport->getBadge(OauthCredentials::class);
        $scopes = $passport->getBadge(OauthScopesBadge::class);

        return new OauthToken(
            $credentials instanceof OauthCredentials ? $credentials->getToken() : '',
            $passport->getUser(),
            $firewallName,
            $passport->getUser()->getRoles(),
            $scopes instanceof OauthScopesBadge ? $scopes->getScopes() : []
        );
    }
}
