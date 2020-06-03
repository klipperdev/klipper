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

use JMS\Serializer\Metadata\PropertyMetadata;
use Klipper\Component\Metadata\AssociationMetadataBuilderInterface;
use Klipper\Component\Metadata\ChildMetadataBuilderInterface;
use Klipper\Component\Metadata\FieldMetadataBuilderInterface;
use Klipper\Component\Metadata\Guess\GuessAssociationConfigInterface;
use Klipper\Component\Metadata\Guess\GuessFieldConfigInterface;
use Metadata\MetadataFactoryInterface;

/**
 * @author François Pluchino <francois.pluchino@klipper.dev>
 */
class GuessJmsSerializerMetadata implements
    GuessFieldConfigInterface,
    GuessAssociationConfigInterface
{
    private MetadataFactoryInterface $factory;

    /**
     * @param MetadataFactoryInterface $factory The jms metadata factory
     */
    public function __construct(MetadataFactoryInterface $factory)
    {
        $this->factory = $factory;
    }

    public function guessFieldConfig(FieldMetadataBuilderInterface $builder): void
    {
        $this->guessChildConfig($builder, $builder->getField());
    }

    public function guessAssociationConfig(AssociationMetadataBuilderInterface $builder): void
    {
        $this->guessChildConfig($builder, $builder->getAssociation());
    }

    /**
     * Guess the config of the child.
     *
     * @param ChildMetadataBuilderInterface $builder  The child builder
     * @param string                        $property The property name
     */
    private function guessChildConfig(ChildMetadataBuilderInterface $builder, string $property): void
    {
        $class = $builder->getParent()->getClass();
        $meta = $this->factory->getMetadataForClass($class);

        if (null !== $meta && isset($meta->propertyMetadata[$property])) {
            /** @var PropertyMetadata $propMeta */
            $propMeta = $meta->propertyMetadata[$property];
            $builder->setPublic(true);

            if ($propMeta->serializedName !== $propMeta->name && null === $builder->getName()) {
                $builder->setName($propMeta->serializedName);
            }

            if ($propMeta->readOnly && null === $builder->isReadOnly()) {
                $builder->setReadOnly(true);
            }

            if (!empty($propMeta->groups)) {
                $builder->setGroups(array_unique(array_merge($builder->getGroups(), $propMeta->groups)));
            }
        }
    }
}
