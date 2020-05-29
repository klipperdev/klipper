<?php

/*
 * This file is part of the Klipper package.
 *
 * (c) François Pluchino <francois.pluchino@klipper.dev>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Klipper\Component\SecurityOauth\Scope\Loader;

/**
 * @author François Pluchino <francois.pluchino@klipper.dev>
 */
class SimpleScopeLoader extends AbstractScopeLoader
{
    /**
     * @var string[]
     */
    private array $scopes;

    public function __construct(array $scopes)
    {
        $this->scopes = $scopes;
    }

    public function load(): array
    {
        return $this->scopes;
    }
}
