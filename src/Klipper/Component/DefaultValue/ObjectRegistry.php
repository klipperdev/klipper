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

use Klipper\Component\DefaultValue\Exception\ExceptionInterface;
use Klipper\Component\DefaultValue\Exception\UnexpectedTypeException;
use Klipper\Component\DefaultValue\Extension\Core\Type\DefaultType;

/**
 * The central registry of the Object Default Value component.
 *
 * @author François Pluchino <francois.pluchino@klipper.dev>
 */
class ObjectRegistry implements ObjectRegistryInterface
{
    /**
     * @var ObjectExtensionInterface[]
     */
    protected array $extensions = [];

    protected array $types = [];

    protected ResolvedObjectTypeFactoryInterface $resolvedTypeFactory;

    /**
     * @param ObjectExtensionInterface[]         $extensions          An array of ObjectExtensionInterface
     * @param ResolvedObjectTypeFactoryInterface $resolvedTypeFactory The factory for resolved object default value types
     *
     * @throws UnexpectedTypeException if any extension does not implement ObjectExtensionInterface
     */
    public function __construct(
        array $extensions,
        ResolvedObjectTypeFactoryInterface $resolvedTypeFactory
    ) {
        foreach ($extensions as $extension) {
            if (!$extension instanceof ObjectExtensionInterface) {
                throw new UnexpectedTypeException($extension, 'Klipper\Component\DefaultValue\ObjectExtensionInterface');
            }
        }

        $this->extensions = $extensions;
        $this->resolvedTypeFactory = $resolvedTypeFactory;
    }

    public function getType(string $classname): ResolvedObjectTypeInterface
    {
        if (!isset($this->types[$classname])) {
            /** @var ObjectTypeInterface $type */
            $type = null;

            foreach ($this->extensions as $extension) {
                /** @var ObjectExtensionInterface $extension */
                if ($extension->hasType($classname)) {
                    $type = $extension->getType($classname);

                    break;
                }
            }

            // fallback to default type
            if (!$type) {
                $type = new DefaultType($classname);
            }

            $this->resolveAndAddType($type);
        }

        return $this->types[$classname];
    }

    public function hasType(string $classname): bool
    {
        if (isset($this->types[$classname])) {
            return true;
        }

        try {
            $this->getType($classname);
        } catch (ExceptionInterface $e) {
            return false;
        }

        return true;
    }

    public function getExtensions(): iterable
    {
        return $this->extensions;
    }

    /**
     * Wraps a type into a ResolvedObjectTypeInterface implementation and connects
     * it with its parent type.
     *
     * @param ObjectTypeInterface $type The type to resolve
     */
    private function resolveAndAddType(ObjectTypeInterface $type): void
    {
        $parentType = $type->getParent();
        $typeExtensions = [];

        foreach ($this->extensions as $extension) {
            /** @var ObjectExtensionInterface $extension */
            $typeExtensions = array_merge(
                $typeExtensions,
                $extension->getTypeExtensions('default'),
                $extension->getTypeExtensions($type->getClass())
            );
        }

        $parent = $parentType ? $this->getType($parentType) : null;
        $rType = $this->resolvedTypeFactory->createResolvedType($type, $typeExtensions, $parent);

        $this->types[$type->getClass()] = $rType;
    }
}
