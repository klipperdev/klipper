<?php

/*
 * This file is part of the Klipper package.
 *
 * (c) François Pluchino <francois.pluchino@klipper.dev>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Klipper\Component\SecurityOauth\Identity;

use Klipper\Component\Security\Identity\AbstractSecurityIdentity;
use Klipper\Component\SecurityOauth\Authentication\Token\OauthToken;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

/**
 * @author François Pluchino <francois.pluchino@klipper.dev>
 */
class OauthScopeSecurityIdentity extends AbstractSecurityIdentity
{
    /**
     * Creates a oauth scope security identity from a ScopeEntityInterface.
     *
     * @param string $scope The oauth scope
     *
     * @return static
     */
    public static function fromAccount(string $scope): self
    {
        return new self('oauth_scope', $scope);
    }

    /**
     * Creates a oauth scope security identity from a TokenInterface.
     *
     * @param TokenInterface $token The token
     *
     * @return static[]
     */
    public static function fromToken(TokenInterface $token): array
    {
        $sids = [];

        if ($token instanceof OauthToken) {
            foreach ($token->getScopes() as $scope) {
                $sids[] = self::fromAccount($scope);
            }
        }

        return $sids;
    }
}
