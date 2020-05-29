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
interface ScopeManagerInterface
{
    /**
     * @return string[]
     */
    public function getScopes(): array;

    public function hasScope(string $scope): bool;

    /**
     * @param string[] $scopes
     */
    public function hasAllScopes(array $scopes): bool;

    /**
     * @param string[] $scopes
     */
    public function hasOneScope(array $scopes): bool;
}
