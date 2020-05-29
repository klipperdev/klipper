<?php

/*
 * This file is part of the Klipper package.
 *
 * (c) François Pluchino <francois.pluchino@klipper.dev>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Klipper\Component\SecurityOauth\Scope;

/**
 * @author François Pluchino <francois.pluchino@klipper.dev>
 */
class ScopeManager implements ScopeManagerInterface
{
    private ScopeRegistryInterface $registry;

    public function __construct(ScopeRegistryInterface $registry)
    {
        $this->registry = $registry;
    }

    public function getScopes(): array
    {
        return $this->registry->getScopes();
    }

    public function hasScope(string $scope): bool
    {
        return \in_array($scope, $this->registry->getScopes(), true);
    }

    public function hasAllScopes(array $scopes): bool
    {
        $availableScopes = $this->registry->getScopes();

        foreach ($scopes as $scope) {
            if (!\in_array($scope, $availableScopes, true)) {
                return false;
            }
        }

        return true;
    }

    public function hasOneScope(array $scopes): bool
    {
        $availableScopes = $this->registry->getScopes();

        foreach ($scopes as $scope) {
            if (\in_array($scope, $availableScopes, true)) {
                return true;
            }
        }

        return false;
    }
}
