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

use Klipper\Component\Metadata\ChildMetadataBuilderInterface;
use Klipper\Component\Metadata\FieldMetadataBuilderInterface;
use Klipper\Component\Metadata\MetadataRegistryInterface;
use Klipper\Component\Metadata\Util\MetadataUtil;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

/**
 * @author François Pluchino <francois.pluchino@klipper.dev>
 */
class GuessChoiceUtil
{
    /**
     * Guess the config of the input and form for the choices.
     *
     * @param MetadataRegistryInterface     $registry The metadata registry
     * @param ChildMetadataBuilderInterface $builder  The child builder
     * @param string                        $class    The class name of target
     * @param bool                          $multiple Check if the choice is multiple
     */
    public static function guessConfig(MetadataRegistryInterface $registry, ChildMetadataBuilderInterface $builder, string $class, bool $multiple, array $criteria = [], ?string $namePath = null): void
    {
        if (null !== $relationBuilder = $registry->getBuilder($class)) {
            $metaName = $relationBuilder->getName() ?? MetadataUtil::getObjectName($class);
            $builder->setInputConfig(array_merge($builder->getInputConfig() ?? [], [
                'choices' => '#/metadatas/'.$metaName,
            ]));

            if (!empty($criteria)) {
                $builder->setInputConfig(array_merge($builder->getInputConfig() ?? [], [
                    'criteria' => $criteria,
                ]));
            }

            if (null !== $namePath) {
                $builder->setInputConfig(array_merge($builder->getInputConfig() ?? [], [
                    'name_path' => $namePath,
                ]));
            }

            if (!$builder instanceof FieldMetadataBuilderInterface) {
                return;
            }

            if ($multiple) {
                $formType = CollectionType::class;
                $formOptions = [
                    'allow_add' => true,
                    'allow_delete' => true,
                    'prototype' => false,
                    'error_bubbling' => false,
                ];
            } else {
                $fieldIdentifier = $relationBuilder->getFieldIdentifier();
                $formType = TextType::class;
                $formOptions = [];

                if (null !== $fieldIdentifier) {
                    $identifierBuilder = $relationBuilder->getField($fieldIdentifier);

                    if (null !== $identifierBuilder && 'number' === $identifierBuilder->getType()) {
                        $formType = NumberType::class;
                        $formOptions = [
                            'scale' => 0,
                        ];
                    }
                }
            }

            $builder->setFormType($formType);
            $builder->setFormOptions($formOptions);
        }
    }
}
