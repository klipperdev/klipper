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
class ScopeRegistry implements ScopeRegistryInterface
{
    private bool $init = false;

    /**
     * @var ScopeLoaderInterface[]
     */
    private array $loaders = [];

    /**
     * @var string[]
     */
    private array $scopes = [];

    /**
     * @param ScopeLoaderInterface[] $loaders
     */
    public function __construct(array $loaders = [])
    {
        foreach ($loaders as $loader) {
            $this->addLoader($loader);
        }
    }

    public function addLoader(ScopeLoaderInterface $loader): self
    {
        $this->loaders[] = $loader;

        return $this;
    }

    public function getScopes(): array
    {
        $this->init();

        return $this->scopes;
    }

    protected function init(): void
    {
        if (!$this->init) {
            $this->init = true;
            $scopes = [$this->scopes];

            foreach ($this->loaders as $loader) {
                $scopes[] = $loader->load();
            }

            $this->scopes = array_values(array_unique(array_merge(...$scopes)));
        }
    }
}
