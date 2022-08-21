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

use Klipper\Component\SecurityOauth\Authenticator\Traits\OauthCreateTokenTrait;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Http\Authenticator\AbstractAuthenticator;
use Symfony\Component\Security\Http\Authenticator\AuthenticatorInterface;
use Symfony\Component\Security\Http\Authenticator\InteractiveAuthenticatorInterface;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;

/**
 * Authenticator for oauth token.
 *
 * @author François Pluchino <francois.pluchino@klipper.dev>
 */
class OauthTokenAuthenticator extends AbstractAuthenticator implements AuthenticatorInterface, InteractiveAuthenticatorInterface
{
    use OauthCreateTokenTrait;

    public function supports(Request $request): bool
    {
        return false;
    }

    public function authenticate(Request $request): Passport
    {
        throw new AuthenticationException('Not use OauthTokenAuthenticator::authenticate() to create token');
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?Response
    {
        return null;
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception): ?Response
    {
        return null;
    }

    public function isInteractive(): bool
    {
        return true;
    }
}
