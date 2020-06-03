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

use Klipper\Component\DefaultValue\Exception\UnexpectedTypeException;

/**
 * @author François Pluchino <francois.pluchino@klipper.dev>
 */
class ObjectFactory implements ObjectFactoryInterface
{
    protected ObjectRegistryInterface $registry;

    protected ResolvedObjectTypeFactoryInterface $resolvedTypeFactory;

    public function __construct(
        ObjectRegistryInterface $registry,
        ResolvedObjectTypeFactoryInterface $resolvedTypeFactory
    ) {
        $this->registry = $registry;
        $this->resolvedTypeFactory = $resolvedTypeFactory;
    }

    public function inject($data, array $options = []): object
    {
        if (!\is_object($data)) {
            throw new UnexpectedTypeException(\gettype($data), 'object');
        }

        return $this->create(\get_class($data), $data, $options);
    }

    public function create($type, ?object $data = null, array $options = []): object
    {
        return $this->createBuilder($type, $data, $options)->getObject();
    }

    public function createBuilder($type, ?object $data = null, array $options = []): ObjectBuilderInterface
    {
        if ($type instanceof ObjectTypeInterface) {
            $type = $this->resolveType($type);
        } elseif (\is_string($type)) {
            $type = $this->registry->getType($type);
        } elseif (!$type instanceof ResolvedObjectTypeInterface) {
            throw new UnexpectedTypeException($type, 'string, Klipper\Component\DefaultValue\ResolvedObjectTypeInterface or Klipper\Component\DefaultValue\ObjectTypeInterface');
        }

        $builder = $type->createBuilder($this, $options);

        if (null !== $data) {
            $builder->setData($data);
        }

        return $builder;
    }

    /**
     * Wraps a type into a ResolvedObjectTypeInterface implementation and connects
     * it with its parent type.
     *
     * @param ObjectTypeInterface $type The type to resolve
     *
     * @return ResolvedObjectTypeInterface The resolved type
     */
    private function resolveType(ObjectTypeInterface $type): ResolvedObjectTypeInterface
    {
        $parentType = $type->getParent();

        if (null !== $parentType) {
            $parentType = $this->registry->getType($parentType);
        }

        return $this->resolvedTypeFactory->createResolvedType(
            $type,
            // Type extensions are not supported for unregistered type instances,
            // i.e. type instances that are passed to the ObjectFactory directly,
            // nor for their parents, if getParent() also returns a type instance.
            [],
            $parentType
        );
    }
}
