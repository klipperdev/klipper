<?php

/*
 * This file is part of the Klipper package.
 *
 * (c) François Pluchino <francois.pluchino@klipper.dev>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Klipper\Component\Metadata;

use Klipper\Component\Metadata\Exception\ActionMetadataNotFoundException;
use Klipper\Component\Metadata\Exception\AssociationMetadataNotFoundException;
use Klipper\Component\Metadata\Exception\FieldMetadataNotFoundException;
use Symfony\Component\Config\Resource\ResourceInterface;

/**
 * @author François Pluchino <francois.pluchino@klipper.dev>
 */
class ObjectMetadata extends BaseMetadata implements ObjectMetadataInterface
{
    public const SORTABLE_DIRECTION = ['asc', 'desc'];

    /**
     * @var string
     */
    protected $pluralName;

    /**
     * @var string
     */
    protected $class;

    /**
     * @var FieldMetadataInterface[]
     */
    protected $fields = [];

    /**
     * @var string[]
     */
    protected $fieldNames = [];

    /**
     * @var AssociationMetadataInterface[]
     */
    protected $associations = [];

    /**
     * @var string[]
     */
    protected $associationNames = [];

    /**
     * @var bool
     */
    protected $sortable = false;

    /**
     * @var bool
     */
    protected $multiSortable;

    /**
     * @var array
     */
    protected $defaultSortable;

    /**
     * @var bool
     */
    protected $filterable = false;

    /**
     * @var bool
     */
    protected $searchable = false;

    /**
     * @var bool
     */
    protected $translatable = false;

    /**
     * @var string[]
     */
    protected $availableContexts;

    /**
     * @var string
     */
    protected $fieldIdentifier;

    /**
     * @var string
     */
    protected $fieldLabel;

    /**
     * @var bool
     */
    protected $buildDefaultActions = true;

    /**
     * @var ActionMetadataInterface[]
     */
    protected $actions = [];

    /**
     * @var ResourceInterface[]
     */
    protected $resources = [];

    public function __construct(
        string $class,
        string $name,
        string $pluralName,
        string $label,
        ?string $description,
        ?string $translationDomain,
        bool $public,
        bool $multiSortable,
        array $defaultSortable,
        array $availableContexts,
        string $fieldIdentifier,
        string $fieldLabel,
        ?string $formType,
        array $formOptions,
        array $groups,
        array $resources,
        array $fieldBuilders,
        array $associationBuilders,
        array $actionBuilders,
        bool $buildDefaultActions
    ) {
        parent::__construct(
            $name,
            'object',
            $label,
            $description,
            $translationDomain,
            $public
        );

        $this->class = $class;
        $this->pluralName = $pluralName;
        $this->multiSortable = $multiSortable;
        $this->defaultSortable = $defaultSortable;
        $this->availableContexts = $availableContexts;
        $this->fieldIdentifier = $fieldIdentifier;
        $this->fieldLabel = $fieldLabel;
        $this->formType = $formType;
        $this->formOptions = $formOptions;
        $this->groups = $groups;
        $this->resources = $resources;
        $this->buildDefaultActions = $buildDefaultActions;

        foreach ($fieldBuilders as $fieldBuilder) {
            $this->addField($fieldBuilder);
        }

        foreach ($associationBuilders as $associationBuilder) {
            $this->addAssociation($associationBuilder);
        }

        foreach ($actionBuilders as $actionBuilder) {
            $this->addAction($actionBuilder);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getPluralName(): string
    {
        return $this->pluralName;
    }

    /**
     * {@inheritdoc}
     */
    public function getClass(): string
    {
        return $this->class;
    }

    /**
     * {@inheritdoc}
     */
    public function getFields(): array
    {
        return $this->fields;
    }

    /**
     * {@inheritdoc}
     */
    public function hasField(string $name): bool
    {
        return isset($this->fields[$name]);
    }

    /**
     * {@inheritdoc}
     */
    public function hasFieldByName(string $name): bool
    {
        return isset($this->fieldNames[$name]);
    }

    /**
     * {@inheritdoc}
     */
    public function getField(string $name): FieldMetadataInterface
    {
        if (!$this->hasField($name)) {
            throw new FieldMetadataNotFoundException($name);
        }

        return $this->fields[$name];
    }

    /**
     * {@inheritdoc}
     */
    public function getFieldByName(string $name): FieldMetadataInterface
    {
        $name = $this->fieldNames[$name] ?? $name;

        return $this->getField($name);
    }

    /**
     * {@inheritdoc}
     */
    public function getAssociations(): array
    {
        return $this->associations;
    }

    /**
     * {@inheritdoc}
     */
    public function hasAssociation(string $name): bool
    {
        return isset($this->associations[$name]);
    }

    /**
     * {@inheritdoc}
     */
    public function hasAssociationByName(string $name): bool
    {
        return isset($this->associationNames[$name]);
    }

    /**
     * {@inheritdoc}
     */
    public function getAssociation(string $name): AssociationMetadataInterface
    {
        if (!$this->hasAssociation($name)) {
            throw new AssociationMetadataNotFoundException($name);
        }

        return $this->associations[$name];
    }

    /**
     * {@inheritdoc}
     */
    public function getAssociationByName(string $name): AssociationMetadataInterface
    {
        $name = $this->associationNames[$name] ?? $name;

        return $this->getAssociation($name);
    }

    /**
     * {@inheritdoc}
     */
    public function isSortable(): bool
    {
        return $this->sortable;
    }

    /**
     * {@inheritdoc}
     */
    public function isMultiSortable(): bool
    {
        return$this->multiSortable;
    }

    /**
     * {@inheritdoc}
     */
    public function getDefaultSortable(): array
    {
        return $this->defaultSortable;
    }

    /**
     * {@inheritdoc}
     */
    public function isFilterable(): bool
    {
        return $this->filterable;
    }

    /**
     * {@inheritdoc}
     */
    public function isSearchable(): bool
    {
        return $this->searchable;
    }

    /**
     * {@inheritdoc}
     */
    public function getAvailableContexts(): array
    {
        return $this->availableContexts;
    }

    /**
     * {@inheritdoc}
     */
    public function getFieldIdentifier(): string
    {
        return $this->fieldIdentifier;
    }

    /**
     * {@inheritdoc}
     */
    public function getFieldLabel(): string
    {
        return $this->fieldLabel;
    }

    /**
     * {@inheritdoc}
     */
    public function getActions(): array
    {
        return $this->actions;
    }

    /**
     * {@inheritdoc}
     */
    public function hasAction(string $action): bool
    {
        return isset($this->actions[$action]);
    }

    /**
     * {@inheritdoc}
     */
    public function getAction(string $action): ActionMetadataInterface
    {
        if (!$this->hasAction($action)) {
            throw new ActionMetadataNotFoundException($action);
        }

        return $this->actions[$action];
    }

    /**
     * {@inheritdoc}
     */
    public function getBuildDefaultActions(): bool
    {
        return $this->buildDefaultActions;
    }

    /**
     * {@inheritdoc}
     */
    public function getResources(): array
    {
        return $this->resources;
    }

    /**
     * Add the field metadata builder.
     *
     * @param FieldMetadataBuilderInterface $builder The field metadata builder
     *
     * @return static
     */
    private function addField(FieldMetadataBuilderInterface $builder): self
    {
        $metadata = $builder->build($this);
        $this->fields[$metadata->getField()] = $metadata;
        $this->fieldNames[$metadata->getName()] = $metadata->getField();

        if ($metadata->isSortable()) {
            $this->sortable = true;
        }

        if ($metadata->isFilterable()) {
            $this->filterable = true;
        }

        if ($metadata->isSearchable()) {
            $this->searchable = true;
        }

        if ($metadata->isTranslatable()) {
            $this->translatable = true;
        }

        return $this;
    }

    /**
     * Add the association metadata builder.
     *
     * @param AssociationMetadataBuilderInterface $builder The association metadata builder
     *
     * @return static
     */
    private function addAssociation(AssociationMetadataBuilderInterface $builder): self
    {
        $metadata = $builder->build($this);
        $this->associations[$metadata->getAssociation()] = $metadata;
        $this->associationNames[$metadata->getName()] = $metadata->getAssociation();

        return $this;
    }

    /**
     * Add the action metadata builder.
     *
     * @param ActionMetadataBuilderInterface $builder The action metadata builder
     *
     * @return static
     */
    private function addAction(ActionMetadataBuilderInterface $builder): self
    {
        $action = $builder->build($this);
        $this->actions[$action->getName()] = $action;

        return $this;
    }
}
