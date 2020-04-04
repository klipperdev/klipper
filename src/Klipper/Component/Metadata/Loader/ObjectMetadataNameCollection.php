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
use Klipper\Component\Metadata\Exception\InvalidArgumentException as MetadataInvalidArgumentException;
use Klipper\Component\Metadata\Util\MetadataUtil;

/**
 * @author François Pluchino <francois.pluchino@klipper.dev>
 */
class ObjectMetadataNameCollection extends AbstractConfigCollection
{
    /**
     * Adds a excluded class.
     *
     * @param string      $class The class name
     * @param null|string $name  The short name
     */
    public function add(string $class, ?string $name): void
    {
        $name = $name ?: MetadataUtil::getObjectName($class);

        if (!isset($this->configs[$name])) {
            $this->configs[$name] = $class;
            ksort($this->configs);
        } else {
            $nameClass = $this->configs[$name];

            if ($nameClass !== $class && !\in_array($class, class_implements($nameClass), true)) {
                throw new MetadataInvalidArgumentException(sprintf('The "%s" metadata name already exist', $name));
            }
        }
    }

    /**
     * {@inheritdoc}
     *
     * @return string[]
     */
    public function all(): array
    {
        return parent::all();
    }

    /**
     * Gets the class name with the short name.
     *
     * @param string $name The short name
     */
    public function get(string $name): ?string
    {
        return $this->configs[$name] ?? null;
    }

    /**
     * Removes the class name with the short name.
     *
     * @param string|string[] $name The short name
     */
    public function remove(string $name): void
    {
        unset($this->configs[$name]);
    }

    /**
     * {@inheritdoc}
     *
     * @param ConfigCollectionInterface|ObjectMetadataNameCollection $collection The collection
     */
    public function addCollection(ConfigCollectionInterface $collection): void
    {
        foreach ($collection->all() as $name => $class) {
            $this->add($class, $name);
        }

        parent::addCollection($collection);
    }
}
