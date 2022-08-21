<?php

/*
 * This file is part of the Klipper package.
 *
 * (c) François Pluchino <francois.pluchino@klipper.dev>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Klipper\Component\SecurityOauth\Authenticator\Passport\Badge;

use Symfony\Component\Security\Http\Authenticator\Passport\Badge\BadgeInterface;

/**
 * @author François Pluchino <francois.pluchino@klipper.dev>
 */
class OauthScopesBadge implements BadgeInterface
{
    /**
     * @var string[]
     */
    private array $scopes;

    /**
     * @param string[] $scopes
     */
    public function __construct(array $scopes)
    {
        $this->scopes = $scopes;
    }

    /**
     * @return string[]
     */
    public function getScopes(): array
    {
        return $this->scopes;
    }

    public function isResolved(): bool
    {
        return true;
    }
}
