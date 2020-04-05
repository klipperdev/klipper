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

use Klipper\Component\Metadata\FieldMetadataBuilderInterface;
use Klipper\Component\Metadata\Guess\GuessFieldConfigInterface;
use Klipper\Component\Metadata\Guess\GuessRegistryAwareInterface;
use Klipper\Component\Metadata\MetadataRegistryInterface;
use Klipper\Component\Metadata\Util\MetadataUtil;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

/**
 * @author François Pluchino <francois.pluchino@klipper.dev>
 */
class GuessInputType implements
    GuessRegistryAwareInterface,
    GuessFieldConfigInterface
{
    /**
     * @var MetadataRegistryInterface
     */
    protected $metadataRegistry;

    /**
     * {@inheritdoc}
     */
    public function setRegistry(MetadataRegistryInterface $registry): void
    {
        $this->metadataRegistry = $registry;
    }

    /**
     * {@inheritdoc}
     */
    public function guessFieldConfig(FieldMetadataBuilderInterface $builder): void
    {
        if (null === $builder->getFormType()) {
            return;
        }

        $this->configMultiple($builder);
        $this->configInputConfigEntityChoice($builder);
    }

    private function configMultiple(FieldMetadataBuilderInterface $builder): void
    {
        if ('object' === $builder->getType() && ($builder->getFormOptions()['multiple'] ?? false)) {
            $builder
                ->setType('array')
                ->setInputConfig(array_merge($builder->getInputConfig() ?? [], [
                    'multiple' => true,
                ]))
            ;
        }
    }

    private function configInputConfigEntityChoice(FieldMetadataBuilderInterface $builder): void
    {
        $form = $builder->getFormType();
        $formOptions = $builder->getFormOptions();

        if (class_exists(EntityType::class) && is_a($form, EntityType::class, true)) {
            if (null === $builder->getInput()) {
                $builder->setInput('choice');
            }

            if (isset($formOptions['class'])) {
                $relationBuilder = $this->metadataRegistry->getBuilder($formOptions['class']);

                if (null !== $relationBuilder) {
                    $class = $relationBuilder->getClass();
                    $metaName = $relationBuilder->getName() ?? MetadataUtil::getObjectName($class);
                    $builder->setInputConfig(array_merge($builder->getInputConfig() ?? [], [
                        'choices' => '#/metadatas/'.$metaName,
                    ]));
                }
            }
        }
    }
}
