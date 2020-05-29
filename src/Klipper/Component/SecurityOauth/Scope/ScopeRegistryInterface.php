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

use Klipper\Component\SecurityOauth\Scope\Loader\ScopeLoaderInterface;

/**
 * @author François Pluchino <francois.pluchino@klipper.dev>
 */
interface ScopeRegistryInterface
{
    /**
     * @return static
     */
    public function addLoader(ScopeLoaderInterface $loader);

    /**
     * @return string[]
     */
    public function getScopes(): array;
}
