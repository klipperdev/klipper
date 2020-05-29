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

    private bool $allowAllScopes;

    public function __construct(ScopeManagerInterface $scopeManager, bool $allowAllScopes = true)
    {
        $this->scopeManager = $scopeManager;
        $this->allowAllScopes = $allowAllScopes;
    }

    public function getScopeEntityByIdentifier($identifier): ?ScopeEntityInterface
    {
        return ($this->allowAllScopes && '*' === $identifier) || $this->scopeManager->hasScope($identifier)
            ? $this->createScope($identifier)
            : null;
    }

    /**
     * @param ScopeEntityInterface[] $scopes
     * @param string                 $grantType
     * @param null|string            $userIdentifier
     *
     * @return ScopeEntityInterface[]
     */
    public function finalizeScopes(array $scopes, $grantType, ClientEntityInterface $clientEntity, $userIdentifier = null): array
    {
        $filteredScopes = [];
        $filteredScopeNames = [];
        $allScopes = false;

        foreach ($scopes as $scope) {
            $identifier = $scope->getIdentifier();

            if ('*' === $identifier) {
                $allScopes = true;
            } elseif ($this->scopeManager->hasScope($identifier)) {
                $filteredScopes[] = $scope;
                $filteredScopeNames[] = $identifier;
            }
        }

        if ($allScopes) {
            foreach ($this->scopeManager->getScopes() as $scopeName) {
                if (!\in_array($scopeName, $filteredScopeNames, true)) {
                    $filteredScopes[] = $this->createScope($scopeName);
                }
            }
        }

        return $filteredScopes;
    }

    private function createScope(string $identifier): ScopeEntityInterface
    {
        return new Scope($identifier);
    }
}
