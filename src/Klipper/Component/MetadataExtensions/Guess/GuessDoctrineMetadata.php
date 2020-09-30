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

use Doctrine\DBAL\Types\Type;
use Doctrine\ORM\Mapping\ClassMetadataInfo as OrmClassMetadata;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Persistence\Mapping\ClassMetadata;
use Klipper\Component\Form\Doctrine\Type\EntityType;
use Klipper\Component\Metadata\AssociationMetadataBuilder;
use Klipper\Component\Metadata\AssociationMetadataBuilderInterface;
use Klipper\Component\Metadata\Exception\InvalidArgumentException as MetadataInvalidArgumentException;
use Klipper\Component\Metadata\FieldMetadataBuilder;
use Klipper\Component\Metadata\FieldMetadataBuilderInterface;
use Klipper\Component\Metadata\Guess\GuessAssociationConfigInterface;
use Klipper\Component\Metadata\Guess\GuessFieldConfigInterface;
use Klipper\Component\Metadata\Guess\GuessObjectConfigInterface;
use Klipper\Component\Metadata\Guess\GuessRegistryAwareInterface;
use Klipper\Component\Metadata\MetadataRegistryInterface;
use Klipper\Component\Metadata\ObjectMetadataBuilderInterface;
use Klipper\Contracts\Model\NameableInterface;

/**
 * @author François Pluchino <francois.pluchino@klipper.dev>
 */
class GuessDoctrineMetadata extends AbstractGuessDoctrine implements
    GuessRegistryAwareInterface,
    GuessObjectConfigInterface,
    GuessFieldConfigInterface,
    GuessAssociationConfigInterface
{
    public const FIELD_TYPES = [
        'guid' => 'uuid',
        'text' => 'string',
        'smallint' => 'integer',
        'bigint' => 'integer',
        'decimal' => 'number',
        'datetime_immutable' => 'datetime',
        'datetimetz' => 'datetime',
        'datetimetz_immutable' => 'datetime',
        'date_immutable' => 'date',
        'time_immutable' => 'time',
        'simple_array' => 'array',
        'json_array' => 'array',
        'json' => 'object',
        'point' => 'object',
        'geometry' => 'object',
        'polygon' => 'object',
        'linestring' => 'object',
    ];

    public const FIELD_TYPE_INPUTS = [
        'object' => 'object',
        'date' => 'date',
        'datetime' => 'datetime',
    ];

    public const ASSOCIATION_TYPES = [
        OrmClassMetadata::ONE_TO_ONE => 'one-to-one',
        OrmClassMetadata::MANY_TO_ONE => 'many-to-one',
        OrmClassMetadata::ONE_TO_MANY => 'one-to-many',
        OrmClassMetadata::MANY_TO_MANY => 'many-to-many',
    ];

    public const SKIP_REQUIRED_TYPES = [
        'boolean',
        'array',
        'simple_array',
        'json',
        'json_array',
    ];

    protected ?MetadataRegistryInterface $metadataRegistry = null;

    protected array $mappingFieldTypes;

    protected array $mappingAssociationTypes;

    protected array $mappingFieldTypeInputs;

    /**
     * @param ManagerRegistry $registry                The doctrine registry
     * @param array           $mappingFieldTypes       The mapping of field types
     * @param array           $mappingAssociationTypes The mapping of association types
     */
    public function __construct(
        ManagerRegistry $registry,
        array $mappingFieldTypes = [],
        array $mappingAssociationTypes = [],
        array $mappingFieldTypeInputs = []
    ) {
        parent::__construct($registry);

        $this->mappingFieldTypes = array_merge(static::FIELD_TYPES, $mappingFieldTypes);
        $this->mappingAssociationTypes = static::ASSOCIATION_TYPES;
        $this->mappingFieldTypeInputs = array_merge(static::FIELD_TYPE_INPUTS, $mappingFieldTypeInputs);

        foreach ($mappingAssociationTypes as $doctrineType => $type) {
            $this->mappingAssociationTypes[$doctrineType] = $type;
        }
    }

    public function setRegistry(MetadataRegistryInterface $registry): void
    {
        $this->metadataRegistry = $registry;
    }

    public function guessObjectConfig(ObjectMetadataBuilderInterface $builder): void
    {
        $classMeta = $this->getClassMetadata($builder->getClass());

        if (null === $classMeta) {
            return;
        }

        if (null === $builder->getFieldIdentifier()) {
            $builder->setFieldIdentifier(current($classMeta->getIdentifierFieldNames()));
        }

        if (null === $builder->getFieldLabel()) {
            if ($classMeta->hasField('label')) {
                $builder->setFieldLabel('label');
            } elseif ($classMeta->hasField('name')) {
                $builder->setFieldLabel('name');
            } else {
                $builder->setFieldLabel($builder->getFieldIdentifier());
            }
        }

        foreach ($classMeta->getFieldNames() as $field) {
            if (!$builder->hasField($field)) {
                $builder->addField(new FieldMetadataBuilder($field));
            }
        }

        foreach ($classMeta->getAssociationNames() as $association) {
            if (!$builder->hasAssociation($association)) {
                $builder->addAssociation(new AssociationMetadataBuilder($association));
            }
        }
    }

    /**
     * @throws
     */
    public function guessFieldConfig(FieldMetadataBuilderInterface $builder): void
    {
        $classMeta = $this->getClassMetadata($builder->getParent()->getClass());
        $fieldName = $builder->getField();

        if (null === $classMeta || !$classMeta->hasField($fieldName)) {
            return;
        }

        if (null === $builder->getType()) {
            $builder->setType($this->getFieldType($classMeta, $fieldName));
        }

        if (null === $builder->getInput() && isset($this->mappingFieldTypeInputs[$builder->getType()])) {
            $builder->setInput($this->mappingFieldTypeInputs[$builder->getType()]);
        }

        if (!$classMeta instanceof OrmClassMetadata) {
            return;
        }

        $mapping = $classMeta->getFieldMapping($fieldName);
        $type = $mapping['type'];

        if (null === $builder->isRequired()) {
            $required = !\in_array($type, static::SKIP_REQUIRED_TYPES, true)
                && (!\array_key_exists('nullable', $mapping) || false === $mapping['nullable']);
            $builder->setRequired($required);
        }
    }

    /**
     * @throws
     */
    public function guessAssociationConfig(AssociationMetadataBuilderInterface $builder): void
    {
        $class = $builder->getParent()->getClass();
        $classMeta = $this->getClassMetadata($class);
        $assoName = $builder->getAssociation();

        if (null === $classMeta || !$classMeta->hasAssociation($assoName)) {
            return;
        }

        $targetClass = $classMeta->getAssociationTargetClass($assoName);

        if (null === $builder->getTarget()) {
            $target = $targetClass;
            $targetName = array_search($target, $this->metadataRegistry->getNames()->all(), true);
            $target = false !== $targetName ? $targetName : $target;
            $builder->setTarget($target);
        }

        if (!$classMeta instanceof OrmClassMetadata) {
            return;
        }

        $mapping = $classMeta->getAssociationMapping($assoName);
        $type = $mapping['type'];
        $multiple = \in_array($type, [OrmClassMetadata::ONE_TO_MANY, OrmClassMetadata::MANY_TO_MANY], true);
        $joins = $mapping['joinColumns'] ?? [];

        if (null === $builder->getType()) {
            $builder->setType($this->getAssociationType($assoName, $type, $class));
        }

        if (null === $builder->isRequired()) {
            $required = \in_array($type, [OrmClassMetadata::ONE_TO_ONE, OrmClassMetadata::MANY_TO_ONE], true)
                && !(isset($joins[0]['nullable']) && true === $joins[0]['nullable']);
            $builder->setRequired($required);
        }

        if (null === $builder->getInput()) {
            $builder->setInput('choice');
            $builder->setInputConfig(array_merge($builder->getInputConfig() ?? [], [
                'multiple' => $multiple,
            ]));

            GuessChoiceUtil::guessConfig(
                $this->metadataRegistry,
                $builder,
                $targetClass,
                $multiple
            );
        }

        $builder->setFormType(EntityType::class);
        $builder->setFormOptions(array_merge($builder->getFormOptions() ?? [], [
            'class' => $targetClass,
            'choice_name' => $this->getAssociationChoiceName($targetClass),
            'multiple' => $multiple,
        ]));
    }

    /**
     * Get the metadata type of field.
     *
     * @param ClassMetadata|OrmClassMetadata $meta  The doctrine class metadata
     * @param string                         $field The field name
     */
    protected function getFieldType(ClassMetadata $meta, string $field): string
    {
        $type = $meta->getTypeOfField($field);
        $type = $type instanceof Type ? $type->getName() : $type;

        if (null === $type) {
            throw new MetadataInvalidArgumentException(sprintf('The metadata field type of "%s::%s" is not defined', $meta->getName(), $field));
        }

        return $this->mappingFieldTypes[$type] ?? $type;
    }

    /**
     * Get the type of association.
     *
     * @param string $association The association name
     * @param int    $type        The doctrine association type
     * @param string $class       The class name
     */
    protected function getAssociationType(string $association, int $type, string $class): string
    {
        if (isset($this->mappingAssociationTypes[$type])) {
            return $this->mappingAssociationTypes[$type];
        }

        throw new MetadataInvalidArgumentException(sprintf('The metadata association type of "%s::%s" is not managed', $class, $association));
    }

    protected function getAssociationChoiceName(string $class): ?string
    {
        if (is_a($class, NameableInterface::class, true)) {
            return 'name';
        }

        return null;
    }
}
