<?php

/*
 * This file is part of the Klipper package.
 *
 * (c) François Pluchino <francois.pluchino@klipper.dev>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Klipper\Component\SecurityOauth\Authorization\Voter;

use Klipper\Component\Security\Authorization\Voter\AbstractIdentityVoter;
use Klipper\Component\SecurityOauth\Annotation\OauthScope;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

/**
 * @author François Pluchino <francois.pluchino@klipper.dev>
 */
class OauthScopeVoter extends AbstractIdentityVoter
{
    protected function getValidType(): string
    {
        return 'oauth_scope';
    }

    protected function getDefaultPrefix(): string
    {
        return 'scope:';
    }

    protected function supports($attribute, $subject): bool
    {
        return parent::supports($attribute, $subject) || $attribute instanceof OauthScope;
    }

    protected function voteOnAttribute($attribute, $subject, TokenInterface $token): bool
    {
        if (!$attribute instanceof OauthScope) {
            return parent::voteOnAttribute($attribute, $subject, $token);
        }

        $tokenScopes = $this->getTokenScopes($token);

        if ($attribute->isAllRequired()) {
            foreach ($attribute->getScope() as $scope) {
                if (!\in_array($scope, $tokenScopes, true)) {
                    return false;
                }
            }
        } else {
            $access = false;

            foreach ($attribute->getScope() as $scope) {
                if (\in_array($scope, $tokenScopes, true)) {
                    $access = true;

                    break;
                }
            }

            if (!$access) {
                return false;
            }
        }

        return true;
    }

    private function getTokenScopes(TokenInterface $token): array
    {
        $sids = $this->sim->getSecurityIdentities($token);
        $scopes = [];

        foreach ($sids as $sid) {
            if ($sid->getType() === $this->getValidType()) {
                $scopes[] = $sid->getIdentifier();
            }
        }

        return $scopes;
    }
}
