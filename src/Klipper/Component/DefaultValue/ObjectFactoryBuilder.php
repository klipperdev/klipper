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

/**
 * The default implementation of ObjectFactoryBuilderInterface.
 *
 * @author François Pluchino <francois.pluchino@klipper.dev>
 */
class ObjectFactoryBuilder implements ObjectFactoryBuilderInterface
{
    private ?ResolvedObjectTypeFactoryInterface $resolvedTypeFactory = null;

    private array $extensions = [];

    private array $types = [];

    private array $typeExtensions = [];

    public function setResolvedTypeFactory(ResolvedObjectTypeFactoryInterface $resolvedTypeFactory): self
    {
        $this->resolvedTypeFactory = $resolvedTypeFactory;

        return $this;
    }

    public function addExtension(ObjectExtensionInterface $extension): self
    {
        $this->extensions[] = $extension;

        return $this;
    }

    public function addExtensions(array $extensions): self
    {
        $this->extensions = array_merge($this->extensions, $extensions);

        return $this;
    }

    public function addType(ObjectTypeInterface $type): self
    {
        $this->types[$type->getClass()] = $type;

        return $this;
    }

    public function addTypes(array $types): self
    {
        /** @var ObjectTypeInterface $type */
        foreach ($types as $type) {
            $this->types[$type->getClass()] = $type;
        }

        return $this;
    }

    public function addTypeExtension(ObjectTypeExtensionInterface $typeExtension): self
    {
        $this->typeExtensions[$typeExtension->getExtendedType()][] = $typeExtension;

        return $this;
    }

    public function addTypeExtensions(array $typeExtensions): self
    {
        /** @var ObjectTypeExtensionInterface $typeExtension */
        foreach ($typeExtensions as $typeExtension) {
            $this->typeExtensions[$typeExtension->getExtendedType()][] = $typeExtension;
        }

        return $this;
    }

    public function getObjectFactory(): ObjectFactoryInterface
    {
        $extensions = $this->extensions;

        if (\count($this->types) > 0 || \count($this->typeExtensions) > 0) {
            $extensions[] = new PreloadedExtension($this->types, $this->typeExtensions);
        }

        $resolvedTypeFactory = $this->resolvedTypeFactory ?: new ResolvedObjectTypeFactory();
        $registry = new ObjectRegistry($extensions, $resolvedTypeFactory);

        return new ObjectFactory($registry, $resolvedTypeFactory);
    }
}
