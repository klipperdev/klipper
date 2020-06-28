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

    protected string $pluralName;

    protected string $class;

    /**
     * @var FieldMetadataInterface[]
     */
    protected array $fields = [];

    /**
     * @var string[]
     */
    protected array $fieldNames = [];

    /**
     * @var AssociationMetadataInterface[]
     */
    protected array $associations = [];

    /**
     * @var string[]
     */
    protected array $associationNames = [];

    protected bool $sortable = false;

    protected bool $multiSortable;

    protected array $defaultSortable;

    protected bool $filterable = false;

    protected bool $searchable = false;

    protected bool $translatable = false;

    /**
     * @var string[]
     */
    protected array $availableContexts;

    protected string $fieldIdentifier;

    protected string $fieldLabel;

    /**
     * @var ActionMetadataInterface[]
     */
    protected array $actions = [];

    /**
     * @var ResourceInterface[]
     */
    protected array $resources = [];

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
        array $actionBuilders
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

    public function getPluralName(): string
    {
        return $this->pluralName;
    }

    public function getClass(): string
    {
        return $this->class;
    }

    public function getFields(): array
    {
        return $this->fields;
    }

    public function hasField(string $name): bool
    {
        return isset($this->fields[$name]);
    }

    public function hasFieldByName(string $name): bool
    {
        return isset($this->fieldNames[$name]);
    }

    public function getField(string $name): FieldMetadataInterface
    {
        if (!$this->hasField($name)) {
            throw new FieldMetadataNotFoundException($name);
        }

        return $this->fields[$name];
    }

    public function getFieldByName(string $name): FieldMetadataInterface
    {
        $name = $this->fieldNames[$name] ?? $name;

        return $this->getField($name);
    }

    public function getAssociations(): array
    {
        return $this->associations;
    }

    public function hasAssociation(string $name): bool
    {
        return isset($this->associations[$name]);
    }

    public function hasAssociationByName(string $name): bool
    {
        return isset($this->associationNames[$name]);
    }

    public function getAssociation(string $name): AssociationMetadataInterface
    {
        if (!$this->hasAssociation($name)) {
            throw new AssociationMetadataNotFoundException($name);
        }

        return $this->associations[$name];
    }

    public function getAssociationByName(string $name): AssociationMetadataInterface
    {
        $name = $this->associationNames[$name] ?? $name;

        return $this->getAssociation($name);
    }

    public function isSortable(): bool
    {
        return $this->sortable;
    }

    public function isMultiSortable(): bool
    {
        return$this->multiSortable;
    }

    public function getDefaultSortable(): array
    {
        return $this->defaultSortable;
    }

    public function isFilterable(): bool
    {
        return $this->filterable;
    }

    public function isSearchable(): bool
    {
        return $this->searchable;
    }

    public function getAvailableContexts(): array
    {
        return $this->availableContexts;
    }

    public function getFieldIdentifier(): string
    {
        return $this->fieldIdentifier;
    }

    public function getFieldLabel(): string
    {
        return $this->fieldLabel;
    }

    public function getActions(): array
    {
        return $this->actions;
    }

    public function hasAction(string $action): bool
    {
        return isset($this->actions[$action]);
    }

    public function getAction(string $action): ActionMetadataInterface
    {
        if (!$this->hasAction($action)) {
            throw new ActionMetadataNotFoundException($action);
        }

        return $this->actions[$action];
    }

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
