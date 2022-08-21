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

use Klipper\Component\SecurityOauth\Annotation\OauthScope;
use Klipper\Component\SecurityOauth\Authentication\Token\OauthToken;
use Klipper\Component\SecurityOauth\Scope\ScopeVote;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\VoterInterface;

/**
 * @author François Pluchino <francois.pluchino@klipper.dev>
 */
class OauthScopeVoter implements VoterInterface
{
    public function vote(TokenInterface $token, $subject, array $attributes): int
    {
        $vote = self::ACCESS_ABSTAIN;

        foreach ($attributes as $attribute) {
            if (!$this->supports($attribute)) {
                continue;
            }

            $vote = self::ACCESS_DENIED;

            if ($this->voteOnAttribute($attribute, $token)) {
                return self::ACCESS_GRANTED;
            }
        }

        return $vote;
    }

    /**
     * @param mixed $attribute
     */
    protected function supports($attribute): bool
    {
        return $attribute instanceof OauthScope
            || (\is_string($attribute) && 0 === strpos($attribute, $this->getPrefix()));
    }

    /**
     * @param OauthScope|string $attribute The attribute
     * @param null|mixed        $subject   The subject
     * @param TokenInterface    $token     The security token
     */
    protected function voteOnAttribute($attribute, TokenInterface $token): bool
    {
        if (!$token instanceof OauthToken) {
            return true;
        }

        $tokenScopes = $token instanceof OauthToken ? $token->getScopes() : [];
        $attribute = \is_string($attribute)
            ? new ScopeVote(substr($attribute, \strlen($this->getPrefix())))
            : $attribute;

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

    protected function getPrefix(): string
    {
        return 'scope:';
    }
}
