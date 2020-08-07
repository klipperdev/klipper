<?php

/*
 * This file is part of the Klipper package.
 *
 * (c) François Pluchino <francois.pluchino@klipper.dev>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Klipper\Component\MetadataExtensions\Guess;

use JMS\Serializer\Metadata\ClassMetadata;
use JMS\Serializer\Metadata\VirtualPropertyMetadata;
use Klipper\Component\Metadata\FieldMetadataBuilder;
use Klipper\Component\Metadata\Guess\GuessObjectConfigInterface;
use Klipper\Component\Metadata\ObjectMetadataBuilderInterface;
use Metadata\MetadataFactoryInterface;

/**
 * @author François Pluchino <francois.pluchino@klipper.dev>
 */
class GuessJmsSerializerVirtualFieldMetadata implements GuessObjectConfigInterface
{
    private MetadataFactoryInterface $factory;

    public function __construct(MetadataFactoryInterface $factory)
    {
        $this->factory = $factory;
    }

    public function guessObjectConfig(ObjectMetadataBuilderInterface $builder): void
    {
        $meta = $this->factory->getMetadataForClass($builder->getClass());

        if ($meta instanceof ClassMetadata) {
            foreach ($meta->propertyMetadata as $propMeta) {
                if ($propMeta instanceof VirtualPropertyMetadata && !$builder->hasField($propMeta->name)) {
                    $field = $builder->hasField($propMeta->name)
                        ? $builder->getField($propMeta->name)
                        : new FieldMetadataBuilder($propMeta->name);

                    $field
                        ->setName($propMeta->serializedName)
                        ->setPublic(true)
                        ->setReadOnly(true)
                        ->setRequired(false)
                        ->setFilterable(false)
                        ->setSearchable(false)
                        ->setSortable(false)
                        ->setTranslatable(false)
                        ->setGroups(array_unique(array_merge($builder->getGroups(), $propMeta->groups ?? [])))
                    ;

                    if (null === $field->getType()) {
                        $propRef = new \ReflectionMethod($propMeta->class, $propMeta->getter);

                        if (null !== $type = $propRef->getReturnType()) {
                            $field->setType($type instanceof \ReflectionNamedType ? $type->getName() : (string) $type);
                        }
                    }

                    if (!$builder->hasField($propMeta->name)) {
                        $builder->addField($field);
                    }
                }
            }
        }
    }
}
