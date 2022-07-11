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

use Klipper\Component\Form\Type\MapValueType;
use Klipper\Component\Geocoder\Form\Type\GeometryPointType;
use Klipper\Component\Metadata\AssociationMetadataBuilderInterface;
use Klipper\Component\Metadata\ChildMetadataBuilderInterface;
use Klipper\Component\Metadata\FieldMetadataBuilderInterface;
use Klipper\Component\Metadata\Guess\GuessAssociationConfigInterface;
use Klipper\Component\Metadata\Guess\GuessFieldConfigInterface;
use Klipper\Component\Metadata\Guess\GuessRegistryAwareInterface;
use Klipper\Component\Metadata\MetadataRegistryInterface;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;

/**
 * @author François Pluchino <francois.pluchino@klipper.dev>
 */
class GuessFormType implements
    GuessRegistryAwareInterface,
    GuessFieldConfigInterface,
    GuessAssociationConfigInterface
{
    public const TYPES = [
        'bic' => TextType::class,
        'card_scheme' => TextType::class,
        'checkbox' => CheckboxType::class,
        'choice' => ChoiceType::class,
        'collection' => CollectionType::class,
        'date' => DateType::class,
        'datetime' => DateTimeType::class,
        'email' => TextType::class,
        'file' => FileType::class,
        'iban' => TextType::class,
        'image' => FileType::class,
        'ip' => TextType::class,
        'isbn' => TextType::class,
        'issn' => TextType::class,
        'json' => TextType::class,
        'luhn' => TextType::class,
        'number' => NumberType::class,
        'object' => MapValueType::class,
        'password' => TextType::class,
        'point' => GeometryPointType::class,
        'text' => TextType::class,
        'textarea' => TextareaType::class,
        'rich_textarea' => TextareaType::class,
        'time' => TimeType::class,
        'url' => TextType::class,
        'uuid' => TextType::class,
    ];

    public const FORM_OPTIONS = [
        'choice' => [GuessFormOptions::class, 'choice'],
        'number' => [
            'scale' => 'inputConfig:scale',
        ],
        'date' => [
            'widget' => 'single_text',
        ],
        'datetime' => [
            'widget' => 'single_text',
        ],
        'time' => [
            'widget' => 'single_text',
            'input_format' => 'H:i',
        ],
    ];

    protected array $mappingTypes;

    protected array $formOptions;

    protected ?MetadataRegistryInterface $metadataRegistry = null;

    /**
     * @param array $mappingInputTypes The mapping between metadata input types and form types
     * @param array $formOptions       The default options of forms
     */
    public function __construct(array $mappingInputTypes = [], array $formOptions = [])
    {
        $this->mappingTypes = array_merge(static::TYPES, $mappingInputTypes);
        $this->formOptions = array_merge(static::FORM_OPTIONS, $formOptions);
    }

    public function setRegistry(MetadataRegistryInterface $registry): void
    {
        $this->metadataRegistry = $registry;
    }

    public function guessFieldConfig(FieldMetadataBuilderInterface $builder): void
    {
        $this->guessChildConfig($builder);
    }

    public function guessAssociationConfig(AssociationMetadataBuilderInterface $builder): void
    {
        $this->guessChildConfig($builder);
    }

    /**
     * Guess the config of the child metadata.
     *
     * @param ChildMetadataBuilderInterface $builder The child metadata builder
     */
    private function guessChildConfig(ChildMetadataBuilderInterface $builder): void
    {
        $input = $builder->getInput();

        if (null !== $input && null === $builder->getFormType()) {
            $typeVal = $this->mappingTypes[$input] ?? null;

            if (\is_callable($typeVal)) {
                $typeVal = $typeVal($builder->getInputConfig() ?? []);
            }

            if (null !== $typeVal && ($builder->getInputConfig()['null_form_type'] ?? false)) {
                $inputConfig = $builder->getInputConfig();
                unset($inputConfig['null_form_type']);
                $builder->setInputConfig($inputConfig);
                $typeVal = null;
            }

            $builder->setFormType($typeVal ?? null);
        }

        if (null !== $input && isset($this->formOptions[$input])) {
            $formConfig = $builder->getFormOptions();
            $inputConfig = $builder->getInputConfig();
            $options = $this->formOptions[$input];

            if (\is_callable($options)) {
                $options = (array) $options($inputConfig ?? [], $builder, $this->metadataRegistry);
            }

            foreach ($options as $option => $value) {
                if (\is_string($value) && 0 === strpos($value, 'inputConfig:')) {
                    $key = substr($value, 12);
                    $value = $inputConfig[$key] ?? null;
                }

                if (null !== $value) {
                    $formConfig[$option] = $value;
                }
            }

            $builder->setFormOptions($formConfig);
        }
    }
}
