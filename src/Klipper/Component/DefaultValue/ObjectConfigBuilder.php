<?php

/*
 * This file is part of the Klipper package.
 *
 * (c) François Pluchino <francois.pluchino@klipper.dev>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Klipper\Component\DefaultValue;

use Klipper\Component\DefaultValue\Exception\BadMethodCallException;
use Klipper\Component\DefaultValue\Exception\InvalidArgumentException;

/**
 * A basic object default value configuration.
 *
 * @author François Pluchino <francois.pluchino@klipper.dev>
 */
class ObjectConfigBuilder implements ObjectConfigBuilderInterface
{
    protected bool $locked = false;

    protected ResolvedObjectTypeInterface $type;

    /**
     * @var mixed
     */
    protected $data;

    protected ?string $dataClass = null;

    protected array $methods = [];

    protected array $properties = [];

    protected array $options;

    /**
     * Creates an empty object default value configuration.
     *
     * @param array $options The object default value options
     */
    public function __construct(array $options = [])
    {
        $this->options = $options;
    }

    public function getType(): ResolvedObjectTypeInterface
    {
        return $this->type;
    }

    public function getProperties(): array
    {
        return $this->properties;
    }

    public function hasProperty(string $name): bool
    {
        return \in_array($name, $this->properties, true);
    }

    public function getProperty(string $name)
    {
        if (null === $this->data) {
            throw new BadMethodCallException('BlockConfigBuilder methods cannot be accessed when the data is empty.');
        }

        $methodGet = 'get'.ucfirst($name);
        $methodHas = 'has'.ucfirst($name);
        $methodIs = 'is'.ucfirst($name);

        if (\in_array($methodGet, $this->methods, true)) {
            return $this->getData()->{$methodGet}();
        }

        if (\in_array($methodHas, $this->methods, true)) {
            return $this->getData()->{$methodHas}();
        }

        if (\in_array($methodIs, $this->methods, true)) {
            return $this->getData()->{$methodIs}();
        }

        if (!$this->hasProperty($name)) {
            throw new InvalidArgumentException(sprintf('The "%s" property does not exist on the "%s" class.', $name, $this->getDataClass()));
        }

        $ref = $this->findReflectionProperty($name, new \ReflectionClass($this->getData()));

        return $ref->getValue($this->getData());
    }

    public function getData()
    {
        return $this->data;
    }

    public function getDataClass(): ?string
    {
        return $this->dataClass;
    }

    public function getOptions(): array
    {
        return $this->options;
    }

    public function hasOption(string $name): bool
    {
        return \array_key_exists($name, $this->options);
    }

    public function getOption(string $name, $default = null)
    {
        return $this->hasOption($name) ? $this->options[$name] : $default;
    }

    public function setType(ResolvedObjectTypeInterface $type): self
    {
        if ($this->locked) {
            throw new BadMethodCallException('BlockConfigBuilder methods cannot be accessed anymore once the builder is turned into a BlockConfigInterface instance.');
        }

        $this->type = $type;

        return $this;
    }

    public function setData($data): self
    {
        if ($this->locked) {
            throw new BadMethodCallException('BlockConfigBuilder methods cannot be accessed anymore once the builder is turned into a BlockConfigInterface instance.');
        }

        if (!\is_object($data)) {
            throw new InvalidArgumentException(sprintf('The data of object default value must be an object, given %s.', \gettype($data)));
        }

        $this->data = $data;
        $this->dataClass = \get_class($data);
        $this->methods = get_class_methods($data);
        $this->properties = [];
        $this->findProperties(new \ReflectionClass($data));

        return $this;
    }

    public function setProperty(string $name, $value): self
    {
        $this->setProperties([$name => $value]);

        return $this;
    }

    public function setProperties(array $properties): self
    {
        if (null === $this->data) {
            throw new BadMethodCallException('BlockConfigBuilder methods cannot be accessed when the data is empty.');
        }

        if ($this->locked) {
            throw new BadMethodCallException('BlockConfigBuilder methods cannot be accessed anymore once the builder is turned into a BlockConfigInterface instance.');
        }

        $refClass = new \ReflectionClass($this->getData());

        foreach ($properties as $property => $value) {
            $methodSet = 'set'.ucfirst($property);

            if (\in_array($methodSet, $this->methods, true)) {
                $this->getData()->{$methodSet}($value);
            } else {
                $refProp = $this->findReflectionProperty($property, $refClass);
                $refProp->setValue($this->getData(), $value);
            }
        }

        return $this;
    }

    public function getObjectConfig(): ObjectConfigInterface
    {
        if ($this->locked) {
            throw new BadMethodCallException('BlockConfigBuilder methods cannot be accessed anymore once the builder is turned into a BlockConfigInterface instance.');
        }

        $this->locked = true;

        return $this;
    }

    /**
     * Finds all properties in class.
     */
    protected function findProperties(\ReflectionClass $reflection): void
    {
        if (false !== $reflection->getParentClass()) {
            $this->findProperties($reflection->getParentClass());
        }

        $this->properties = array_unique(array_merge($this->properties, array_keys($reflection->getDefaultProperties())));
    }

    /**
     * Finds the reflection property.
     *
     * @throws InvalidArgumentException When the property is not found
     */
    protected function findReflectionProperty(string $property, \ReflectionClass $reflection): \ReflectionProperty
    {
        if ($reflection->hasProperty($property)) {
            $refProp = $reflection->getProperty($property);
            $refProp->setAccessible(true);

            return $refProp;
        }

        if (false !== $reflection->getParentClass()) {
            return $this->findReflectionProperty($property, $reflection->getParentClass());
        }

        throw new InvalidArgumentException(sprintf('The "%s" property is not found in "%s" class.', $property, $this->getDataClass()));
    }
}
