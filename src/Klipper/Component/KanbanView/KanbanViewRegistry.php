<?php

/*
 * This file is part of the Klipper package.
 *
 * (c) François Pluchino <francois.pluchino@klipper.dev>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Klipper\Component\KanbanView;

use Klipper\Component\KanbanView\Exception\KanbanViewNotFoundException;
use Klipper\Component\KanbanView\Loader\KanbanViewLoaderInterface;

/**
 * @author François Pluchino <francois.pluchino@klipper.dev>
 */
class KanbanViewRegistry implements KanbanViewRegistryInterface
{
    /**
     * @var KanbanViewLoaderInterface[]
     */
    private array $loaders = [];

    /**
     * @var array<string, array<string, false|KanbanViewInterface>>
     */
    private array $views = [];

    public function __construct(array $loaders = [])
    {
        foreach ($loaders as $loader) {
            $this->addLoader($loader);
        }
    }

    public function addLoader(KanbanViewLoaderInterface $loader): void
    {
        $this->loaders[] = $loader;
    }

    public function registerView(KanbanViewInterface $view): self
    {
        $this->views[$view->getType()][$view->getName()] = $view;

        return $this;
    }

    public function unregisterView(string $type, string $name): self
    {
        unset($this->views[$type][$name]);

        return $this;
    }

    public function hasView(string $type, string $name): bool
    {
        if (isset($this->views[$type][$name])) {
            return false !== $this->views[$type][$name];
        }

        $this->load($type, $name);

        return false !== $this->views[$type][$name];
    }

    public function getView(string $type, string $name): KanbanViewInterface
    {
        if ($this->hasView($type, $name)) {
            return $this->views[$type][$name];
        }

        throw new KanbanViewNotFoundException($type, $name);
    }

    private function load(string $type, string $name): void
    {
        if (isset($this->views[$type][$name]) && false !== $this->views[$type][$name]) {
            return;
        }

        $this->views[$type][$name] = false;

        foreach ($this->loaders as $loader) {
            if ($loader->supports($type, $name)) {
                $views = $loader->load($type, $name);

                foreach ($views as $view) {
                    $this->views[$view->getType()][$view->getName()] = $view;
                }

                break;
            }
        }
    }
}
