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

use Klipper\Component\Metadata\AssociationMetadataBuilderInterface;
use Klipper\Component\Metadata\FieldMetadataBuilderInterface;
use Klipper\Component\Metadata\Guess\GuessAssociationConfigInterface;
use Klipper\Component\Metadata\Guess\GuessFieldConfigInterface;
use Klipper\Component\Metadata\Guess\GuessObjectConfigInterface;
use Klipper\Component\Metadata\MetadataBuilderInterface;
use Klipper\Component\Metadata\ObjectMetadataBuilderInterface;
use Klipper\Component\Metadata\Util\MetadataUtil;
use Symfony\Component\Translation\MessageCatalogueInterface;
use Symfony\Component\Translation\TranslatorBagInterface;

/**
 * @author François Pluchino <francois.pluchino@klipper.dev>
 */
class GuessTranslator implements
    GuessObjectConfigInterface,
    GuessFieldConfigInterface,
    GuessAssociationConfigInterface
{
    private TranslatorBagInterface $translator;

    public function __construct(TranslatorBagInterface $translator)
    {
        $this->translator = $translator;
    }

    public function guessObjectConfig(ObjectMetadataBuilderInterface $builder): void
    {
        $name = $builder->getName() ?? MetadataUtil::getObjectName($builder->getClass());

        $this->guessTranslations($builder, [$name.'.name'], [$name.'.description']);
    }

    public function guessFieldConfig(FieldMetadataBuilderInterface $builder): void
    {
        $parentBuilder = $builder->getParent();
        $name = $parentBuilder->getName() ?? MetadataUtil::getObjectName($parentBuilder->getClass());
        $fieldName = $builder->getName() ? MetadataUtil::getObjectName($builder->getName()) : $builder->getField();
        $field = MetadataUtil::getObjectName($builder->getField());

        $this->guessTranslations($builder, [
            $name.'.fields.'.$fieldName,
            $name.'.fields.'.$field,
            'standard.fields.'.$fieldName,
            'standard.fields.'.$field,
        ], [
            $name.'.fields.'.$fieldName.'.description',
            $name.'.fields.'.$field.'.description',
            'standard.fields.'.$fieldName.'.description',
            'standard.fields.'.$field.'.description',
        ]);
    }

    public function guessAssociationConfig(AssociationMetadataBuilderInterface $builder): void
    {
        $parentBuilder = $builder->getParent();
        $name = $parentBuilder->getName() ?? MetadataUtil::getObjectName($parentBuilder->getClass());
        $assoName = $builder->getName() ? MetadataUtil::getObjectName($builder->getName()) : $builder->getAssociation();
        $asso = MetadataUtil::getObjectName($builder->getAssociation());
        $plural = \in_array($builder->getType(), ['one-to-many', 'many-to-many'], true) ? '.plural' : '';

        $this->guessTranslations($builder, [
            $name.'.associations.'.$assoName,
            $name.'.associations.'.$asso,
            'standard.associations.'.$assoName,
            'standard.associations.'.$asso,
            $builder->getTarget().'.name'.$plural,
            $builder->getTarget().'.name',
        ], [
            $name.'.associations.'.$assoName.'.description',
            $name.'.associations.'.$asso.'.description',
            'standard.associations.'.$assoName.'.description',
            'standard.associations.'.$asso.'.description',
            $builder->getTarget().'.description'.$plural,
            $builder->getTarget().'.description',
        ]);
    }

    private function guessTranslations(MetadataBuilderInterface $builder, array $names, array $descriptions): void
    {
        if (null === $builder->getTranslationDomain()) {
            $catalogue = $this->translator->getCatalogue();

            if (null === $builder->getLabel()) {
                $builder->setLabel($this->findTranslation($catalogue, $builder, $names));
            }

            if (null === $builder->getDescription()) {
                $builder->setDescription($this->findTranslation($catalogue, $builder, $descriptions));
            }
        }
    }

    private function findTranslation(
        MessageCatalogueInterface $catalogue,
        MetadataBuilderInterface $builder,
        array $names
    ): ?string {
        $translation = null;

        foreach ($names as $name) {
            if ($catalogue->has($name, 'entities')) {
                $builder->setTranslationDomain('entities');
                $translation = $name;

                break;
            }
        }

        return $translation;
    }
}
