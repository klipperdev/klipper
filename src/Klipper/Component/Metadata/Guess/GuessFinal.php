<?php

/*
 * This file is part of the Klipper package.
 *
 * (c) François Pluchino <francois.pluchino@klipper.dev>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Klipper\Component\Metadata\Guess;

use Klipper\Component\Metadata\AssociationMetadataBuilderInterface;
use Klipper\Component\Metadata\ChildMetadataBuilderInterface;
use Klipper\Component\Metadata\FieldMetadataBuilderInterface;
use Klipper\Component\Metadata\MetadataContexts;
use Klipper\Component\Metadata\ObjectMetadataBuilderInterface;
use Klipper\Component\Metadata\Util\MetadataUtil;
use Symfony\Component\String\Inflector\EnglishInflector;

/**
 * @author François Pluchino <francois.pluchino@klipper.dev>
 */
class GuessFinal implements
    GuessObjectConfigInterface,
    GuessFieldConfigInterface,
    GuessAssociationConfigInterface
{
    public function guessObjectConfig(ObjectMetadataBuilderInterface $builder): void
    {
        $inflector = new EnglishInflector();
        $class = $builder->getClass();
        $label = $builder->getLabel();

        $builder->setName($builder->getName() ?? MetadataUtil::getObjectName($class));
        $builder->setTranslationDomain(null !== $label ? $builder->getTranslationDomain() : null);
        $builder->setPublic($builder->isPublic() ?? false);

        $builder->setPluralName($builder->getPluralName() ?? (string) current($inflector->pluralize($builder->getName())));
        $builder->setMultiSortable($builder->isMultiSortable() ?? false);
        $builder->setAvailableContexts($builder->getAvailableContexts() ?? [
            MetadataContexts::USER,
            MetadataContexts::ORGANIZATION,
        ]);
        $builder->setFieldIdentifier($builder->getFieldIdentifier() ?? 'id');
        $builder->setFieldLabel($builder->getFieldLabel() ?? 'id');
        $builder->setDefaultSortable($builder->getDefaultSortable() ?? [$builder->getFieldLabel() => 'asc']);

        if (null === $builder->getActions()) {
            $builder->setActions([]);
        }

        if (null === $label) {
            $builder->setLabel(MetadataUtil::getObjectLabel($builder->getName()));
        }

        $builder->setPluralLabel($builder->getPluralLabel() ?? (string) current($inflector->pluralize($builder->getLabel())));
    }

    public function guessFieldConfig(FieldMetadataBuilderInterface $builder): void
    {
        $builder->setSortable($builder->isSortable() ?? false);
        $builder->setFilterable($builder->isFilterable() ?? false);
        $builder->setSearchable($builder->isSearchable() ?? false);
        $builder->setTranslatable($builder->isTranslatable() ?? false);

        $this->guessChildConfig($builder, $builder->getField());
    }

    public function guessAssociationConfig(AssociationMetadataBuilderInterface $builder): void
    {
        $builder->setTarget($builder->getTarget() ?? '');

        $this->guessChildConfig($builder, $builder->getAssociation());
    }

    private function guessChildConfig(ChildMetadataBuilderInterface $builder, string $property): void
    {
        $label = $builder->getLabel();

        $builder->setName($builder->getName() ?? MetadataUtil::getObjectName($property));
        $builder->setType($builder->getType() ?? '');
        $builder->setTranslationDomain(null !== $label ? $builder->getTranslationDomain() : null);
        $builder->setPublic($builder->isPublic() ?? false);
        $builder->setReadOnly($builder->isReadOnly() ?? false);
        $builder->setRequired($builder->isRequired() ?? false);

        if (null === $label) {
            $builder->setLabel(MetadataUtil::getObjectLabel($builder->getName()));
        }

        if ($builder->isReadOnly()) {
            $builder->setRequired(false);
            $builder->setInput(null);
            $builder->setInputConfig(null);
            $builder->setFormType(null);
            $builder->setFormOptions([]);
        }
    }
}
