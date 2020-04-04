<?php

/*
 * This file is part of the Klipper package.
 *
 * (c) François Pluchino <francois.pluchino@klipper.dev>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Klipper\Component\Metadata;

use Klipper\Component\Metadata\Exception\ObjectMetadataNotFoundException;

/**
 * @author François Pluchino <francois.pluchino@klipper.dev>
 */
class MetadataManager implements MetadataManagerInterface
{
    /**
     * @var MetadataFactoryInterface
     */
    protected $factory;

    /**
     * @var null|ObjectMetadataInterface[]
     */
    protected $objects;

    /**
     * @var null|ChoiceInterface[]
     */
    protected $choices;

    /**
     * Constructor.
     *
     * @param MetadataFactoryInterface $factory The metadata factory
     */
    public function __construct(MetadataFactoryInterface $factory)
    {
        $this->factory = $factory;
    }

    /**
     * {@inheritdoc}
     */
    public function all(): array
    {
        if (null === $this->objects) {
            $this->objects = [];

            foreach ($this->factory->getManagedClasses()->all() as $class) {
                $this->get($class);
            }
        }

        return $this->objects;
    }

    /**
     * {@inheritdoc}
     */
    public function has(string $class): bool
    {
        return $this->factory->isManagedClass($class);
    }

    /**
     * {@inheritdoc}
     */
    public function hasByName(string $name): bool
    {
        return $this->factory->isManagedByName($name);
    }

    /**
     * {@inheritdoc}
     */
    public function get(string $class): ObjectMetadataInterface
    {
        $class = $this->factory->getManagedClass($class);

        if (!isset($this->objects[$class])) {
            $meta = $this->factory->create($class);
            $this->objects[$class] = $meta;
        }

        return $this->objects[$class];
    }

    /**
     * {@inheritdoc}
     */
    public function getByName(string $name): ObjectMetadataInterface
    {
        if ($this->hasByName($name)) {
            return $this->get($name);
        }

        throw new ObjectMetadataNotFoundException($name);
    }

    /**
     * {@inheritdoc}
     */
    public function allChoices(): array
    {
        if (null === $this->choices) {
            $this->choices = [];

            foreach ($this->factory->getChoiceNames()->all() as $name) {
                $this->getChoice($name);
            }
        }

        return $this->choices;
    }

    /**
     * {@inheritdoc}
     */
    public function hasChoice(string $name): bool
    {
        return $this->factory->isChoiceManaged($name);
    }

    /**
     * {@inheritdoc}
     */
    public function getChoice(string $name): ChoiceInterface
    {
        if (!isset($this->choices[$name])) {
            $choice = $this->factory->createChoice($name);
            $this->choices[$name] = $choice;
        }

        return $this->choices[$name];
    }
}
