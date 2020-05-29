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

use Klipper\Component\SecurityOauth\Scope\ScopeManagerInterface;
use League\OAuth2\Server\Entities\ClientEntityInterface;
use League\OAuth2\Server\Entities\ScopeEntityInterface;
use League\OAuth2\Server\Repositories\ScopeRepositoryInterface;

/**
 * @author François Pluchino <francois.pluchino@klipper.dev>
 */
class ScopeRepository implements ScopeRepositoryInterface
{
    private ScopeManagerInterface $scopeManager;

    public function __construct(ScopeManagerInterface $scopeManager)
    {
        $this->scopeManager = $scopeManager;
    }

    public function getScopeEntityByIdentifier($identifier): ?Scope
    {
        return $this->scopeManager->hasScope($identifier) ? new Scope($identifier) : null;
    }

    /**
     * @param Scope[]   $scopes
     * @param string    $grantType
     * @param null|null $userIdentifier
     *
     * @return ScopeEntityInterface[]
     */
    public function finalizeScopes(array $scopes, $grantType, ClientEntityInterface $clientEntity, $userIdentifier = null): array
    {
        $filteredScopes = [];

        foreach ($scopes as $scope) {
            if ($this->scopeManager->hasScope($scope->getIdentifier())) {
                $filteredScopes[] = $scope;
            }
        }

        return $filteredScopes;
    }
}
