<?php

/*
 * This file is part of the Klipper package.
 *
 * (c) François Pluchino <francois.pluchino@klipper.dev>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Klipper\Component\SecurityOauth\Bridge;

use League\OAuth2\Server\Entities\ClientEntityInterface;
use League\OAuth2\Server\Repositories\ScopeRepositoryInterface;

/**
 * @author François Pluchino <francois.pluchino@klipper.dev>
 */
class ScopeRepository implements ScopeRepositoryInterface
{
    public function getScopeEntityByIdentifier($identifier): ?Scope
    {
        return Scope::hasScope($identifier) ? new Scope($identifier) : null;
    }

    public function finalizeScopes(array $scopes, $grantType, ClientEntityInterface $clientEntity, $userIdentifier = null)
    {
        $filteredScopes = [];

        /** @var Scope $scope */
        foreach ($scopes as $scope) {
            $hasScope = Scope::hasScope($scope->getIdentifier());

            if ($hasScope) {
                $filteredScopes[] = $scope;
            }
        }

        return $filteredScopes;
    }
}
