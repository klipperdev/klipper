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

use Klipper\Component\DefaultValue\Exception\InvalidArgumentException;
use Klipper\Component\DefaultValue\Exception\UnexpectedTypeException;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * A wrapper for a object default value type and its extensions.
 *
 * @author François Pluchino <francois.pluchino@klipper.dev>
 */
class ResolvedObjectType implements ResolvedObjectTypeInterface
{
    protected ObjectTypeInterface $innerType;

    /**
     * @var ObjectTypeExtensionInterface[]
     */
    protected array $typeExtensions;

    protected ?ResolvedObjectTypeInterface $parent;

    protected ?OptionsResolver $optionsResolver = null;

    /**
     * @param ObjectTypeExtensionInterface[] $typeExtensions
     *
     * @throws InvalidArgumentException When the object default value type classname does not exist
     * @throws UnexpectedTypeException  When unexpected type of argument
     */
    public function __construct(
        ObjectTypeInterface $innerType,
        array $typeExtensions = [],
        ?ResolvedObjectTypeInterface $parent = null
    ) {
        if ('default' !== $innerType->getClass() && !class_exists($innerType->getClass())) {
            throw new InvalidArgumentException(sprintf(
                'The "%s" object default value type class name ("%s") does not exists.',
                \get_class($innerType),
                $innerType->getClass()
            ));
        }

        foreach ($typeExtensions as $extension) {
            if (!$extension instanceof ObjectTypeExtensionInterface) {
                throw new UnexpectedTypeException($extension, 'Klipper\Component\DefaultValue\ObjectTypeExtensionInterface');
            }
        }

        $this->innerType = $innerType;
        $this->typeExtensions = $typeExtensions;
        $this->parent = $parent;
    }

    public function getClass(): string
    {
        return $this->innerType->getClass();
    }

    public function getParent(): ResolvedObjectTypeInterface
    {
        return $this->parent;
    }

    public function getInnerType(): ObjectTypeInterface
    {
        return $this->innerType;
    }

    public function getTypeExtensions(): iterable
    {
        return $this->typeExtensions;
    }

    public function createBuilder(ObjectFactoryInterface $factory, array $options = []): ObjectBuilderInterface
    {
        $options = $this->getOptionsResolver()->resolve($options);
        $builder = new ObjectBuilder($factory, $options);
        $builder->setType($this);

        return $builder;
    }

    public function newInstance(ObjectBuilderInterface $builder, array $options): ?object
    {
        $data = $this->innerType->newInstance($builder, $options);

        if (null === $data && null !== $this->parent) {
            $data = $this->parent->newInstance($builder, $options);
        }

        return $data;
    }

    public function buildObject(ObjectBuilderInterface $builder, array $options): void
    {
        $this->doActionObject('buildObject', $builder, $options);
    }

    public function finishObject(ObjectBuilderInterface $builder, array $options): void
    {
        $this->doActionObject('finishObject', $builder, $options);
    }

    public function getOptionsResolver(): OptionsResolver
    {
        if (null === $this->optionsResolver) {
            if (null !== $this->parent) {
                $this->optionsResolver = clone $this->parent->getOptionsResolver();
            } else {
                $this->optionsResolver = new OptionsResolver();
            }

            $this->innerType->configureOptions($this->optionsResolver);

            foreach ($this->typeExtensions as $extension) {
                /* @var ObjectTypeExtensionInterface $extension */
                $extension->configureOptions($this->optionsResolver);
            }
        }

        return $this->optionsResolver;
    }

    /**
     * Build or finish the object.
     *
     * @param string $method The buildObject or finishObject method name
     */
    protected function doActionObject(string $method, ObjectBuilderInterface $builder, array $options): void
    {
        if (null !== $this->parent) {
            $this->parent->{$method}($builder, $options);
        }

        $this->innerType->{$method}($builder, $options);

        foreach ($this->typeExtensions as $extension) {
            /* @var ObjectTypeExtensionInterface $extension */
            $extension->{$method}($builder, $options);
        }
    }
}
