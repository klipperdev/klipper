<?php

/*
 * This file is part of the Klipper package.
 *
 * (c) François Pluchino <francois.pluchino@klipper.dev>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Klipper\Component\Metadata\Loader;

use Klipper\Component\Config\AbstractConfigCollection;
use Klipper\Component\Config\ConfigCollectionInterface;

/**
 * @author François Pluchino <francois.pluchino@klipper.dev>
 */
class ChoiceNameCollection extends AbstractConfigCollection
{
    /**
     * Adds a excluded class.
     *
     * @param string $class A excluded class name
     */
    public function add(string $class): void
    {
        if (!\in_array($class, $this->configs, true)) {
            $this->configs[] = $class;
            sort($this->configs);
        }
    }

    /**
     * @return string[]
     */
    public function all(): array
    {
        return parent::all();
    }

    /**
     * Gets a excluded class name or null if the class is not excluded.
     *
     * @param string $class The class name
     */
    public function get(string $class): ?string
    {
        return \in_array($class, $this->configs, true) ? $class : null;
    }

    /**
     * Removes a excluded class.
     *
     * @param string|string[] $class
     */
    public function remove(string $class): void
    {
        $this->configs = array_values(array_diff($this->configs, (array) $class));
    }

    /**
     * @param ChoiceNameCollection|ConfigCollectionInterface $collection The collection
     */
    public function addCollection(ConfigCollectionInterface $collection): void
    {
        foreach ($collection->all() as $config) {
            $this->add($config);
        }

        parent::addCollection($collection);
    }
}
