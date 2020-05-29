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
use Symfony\Component\Config\Resource\ResourceInterface;

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
     * @var ResourceInterface[]
     */
    private array $resources = [];

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
        $this->init = false;
        $this->scopes = [];
        $this->resources = [];
        $this->loaders[] = $loader;

        return $this;
    }

    public function getScopes(): array
    {
        $this->init();

        return $this->scopes;
    }

    public function getResources(): array
    {
        $this->init();

        return array_values($this->resources);
    }

    protected function init(): void
    {
        if (!$this->init) {
            $this->init = true;
            $scopes = [$this->scopes];

            foreach ($this->loaders as $loader) {
                $scopes[] = $loader->load();

                foreach ($loader->getResources() as $resource) {
                    $key = (string) $resource;

                    if (!isset($this->resources[$key])) {
                        $this->resources[$key] = $resource;
                    }
                }
            }

            $this->scopes = array_values(array_unique(array_merge(...$scopes)));
        }
    }
}
