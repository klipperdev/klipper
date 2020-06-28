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

use Klipper\Component\Metadata\Util\BuilderUtil;
use Symfony\Component\Config\Resource\ResourceInterface;

/**
 * @author François Pluchino <francois.pluchino@klipper.dev>
 */
class ObjectMetadataBuilder extends BaseMetadataBuilder implements ObjectMetadataBuilderInterface
{
    protected ?string $pluralName = null;

    protected string $class;

    /**
     * @var FieldMetadataBuilderInterface[]
     */
    protected array $fields = [];

    /**
     * @var AssociationMetadataBuilderInterface[]
     */
    protected array $associations = [];

    protected bool $sortable = false;

    protected ?bool $multiSortable = null;

    protected ?array $defaultSortable = null;

    /**
     * @var null|string[]
     */
    protected ?array $availableContexts = null;

    protected ?string $fieldIdentifier = null;

    protected ?string $fieldLabel = null;

    /**
     * @var null|ActionMetadataBuilderInterface[]
     */
    protected ?array $actions = null;

    protected ?bool $buildDefaultActions = null;

    protected array $excludedDefaultActions = [];

    protected ?ActionMetadataBuilderInterface $defaultAction = null;

    /**
     * @var ResourceInterface[]
     */
    protected array $resources = [];

    /**
     * @param string $class The class name
     */
    public function __construct(string $class)
    {
        parent::__construct('object');

        $this->class = $class;
    }

    public function setPluralName(?string $name): self
    {
        $this->pluralName = $name;

        return $this;
    }

    public function getPluralName(): ?string
    {
        return $this->pluralName;
    }

    public function getClass(): string
    {
        return $this->class;
    }

    public function addField(FieldMetadataBuilderInterface $metadata): self
    {
        $metadata->setParent($this);

        if (isset($this->fields[$metadata->getField()])) {
            $this->fields[$metadata->getField()]->merge($metadata);
        } else {
            $this->fields[$metadata->getField()] = $metadata;
            ksort($this->fields);
        }

        if ($metadata->isSortable()) {
            $this->sortable = true;
        }

        return $this;
    }

    public function hasField(string $field): bool
    {
        return isset($this->fields[$field]);
    }

    public function getField(string $field): ?FieldMetadataBuilderInterface
    {
        return $this->fields[$field] ?? null;
    }

    public function getFields(): array
    {
        return $this->fields;
    }

    public function addAssociation(AssociationMetadataBuilderInterface $metadata): self
    {
        $metadata->setParent($this);

        if (isset($this->associations[$metadata->getAssociation()])) {
            $this->associations[$metadata->getAssociation()]->merge($metadata);
        } else {
            $this->associations[$metadata->getAssociation()] = $metadata;
            ksort($this->associations);
        }

        return $this;
    }

    public function hasAssociation(string $association): bool
    {
        return isset($this->associations[$association]);
    }

    public function getAssociation(string $association): ?AssociationMetadataBuilderInterface
    {
        return $this->associations[$association] ?? null;
    }

    public function getAssociations(): array
    {
        return $this->associations;
    }

    public function isSortable(): bool
    {
        return $this->sortable;
    }

    public function setMultiSortable(?bool $sortable): self
    {
        $this->multiSortable = $sortable;

        return $this;
    }

    public function isMultiSortable(): ?bool
    {
        return$this->multiSortable;
    }

    public function setDefaultSortable(?array $map): self
    {
        $this->defaultSortable = $map;

        return $this;
    }

    public function getDefaultSortable(): ?array
    {
        return $this->defaultSortable;
    }

    public function setAvailableContexts(?array $availableContexts): self
    {
        $this->availableContexts = $availableContexts;

        return $this;
    }

    public function getAvailableContexts(): ?array
    {
        return $this->availableContexts;
    }

    public function setFieldIdentifier(?string $name): self
    {
        $this->fieldIdentifier = $name;

        return $this;
    }

    public function getFieldIdentifier(): ?string
    {
        return $this->fieldIdentifier;
    }

    public function setFieldLabel(?string $name): self
    {
        $this->fieldLabel = $name;

        return $this;
    }

    public function getFieldLabel(): ?string
    {
        return $this->fieldLabel;
    }

    public function hasAction(string $action): bool
    {
        return isset($this->actions[$action]);
    }

    public function setActions(?array $actions): self
    {
        $this->actions = $actions;

        if (null !== $this->actions) {
            foreach ($this->actions as $action) {
                $this->addAction($action);
            }
        }

        return $this;
    }

    public function addAction(ActionMetadataBuilderInterface $action): self
    {
        $action->setParent($this);

        if (isset($this->actions[$action->getName()])) {
            $this->actions[$action->getName()]->merge($action);
        } else {
            $this->actions[$action->getName()] = $action;
        }

        return $this;
    }

    public function getActions(): ?array
    {
        return $this->actions;
    }

    public function getAction(string $action): ?ActionMetadataBuilderInterface
    {
        return $this->actions[$action] ?? null;
    }

    public function setBuildDefaultActions(?bool $buildDefaultActions): self
    {
        $this->buildDefaultActions = $buildDefaultActions;

        return $this;
    }

    public function getBuildDefaultActions(): ?bool
    {
        return $this->buildDefaultActions;
    }

    public function setExcludedDefaultActions(array $excludedDefaultActions)
    {
        $this->excludedDefaultActions = $excludedDefaultActions;

        return $this;
    }

    public function getExcludedDefaultActions(): array
    {
        return $this->excludedDefaultActions;
    }

    public function setDefaultAction(?ActionMetadataBuilderInterface $action): self
    {
        if (null !== $action) {
            $action->setParent($this);
            $action->setName('_default');
        }

        if (null !== $action && null !== $this->defaultAction) {
            $this->defaultAction->merge($action);
        } else {
            $this->defaultAction = $action;
        }

        return $this;
    }

    public function getDefaultAction(): ?ActionMetadataBuilderInterface
    {
        return $this->defaultAction;
    }

    public function getResources(): array
    {
        return array_values($this->resources);
    }

    public function addResource(ResourceInterface $resource): void
    {
        $key = (string) $resource;

        if (!isset($this->resources[$key])) {
            $this->resources[$key] = $resource;
        }
    }

    public function merge(ObjectMetadataBuilderInterface $builder): self
    {
        BuilderUtil::mergeValues($this, $builder);

        foreach ($builder->getFields() as $field) {
            $this->addField($field);
        }

        foreach ($builder->getAssociations() as $association) {
            $this->addAssociation($association);
        }

        if (null !== $defaultAction = $builder->getDefaultAction()) {
            $this->setDefaultAction($defaultAction);
        }

        if (null !== $actions = $builder->getActions()) {
            foreach ($actions as $action) {
                $this->addAction($action);
            }
        }

        foreach ($builder->getResources() as $resource) {
            $this->addResource($resource);
        }

        return $this;
    }

    public function build(): ObjectMetadataInterface
    {
        BuilderUtil::validate($this, ['name', 'pluralName', 'label', 'fieldIdentifier', 'fieldLabel']);

        return new ObjectMetadata(
            $this->getClass(),
            (string) $this->getName(),
            (string) $this->getPluralName(),
            (string) $this->getLabel(),
            $this->getDescription(),
            $this->getTranslationDomain(),
            $this->isPublic() ?? false,
            $this->isMultiSortable() ?? false,
            $this->getDefaultSortable() ?? [],
            $this->getAvailableContexts() ?? [],
            (string) $this->getFieldIdentifier(),
            (string) $this->getFieldLabel(),
            $this->getFormType(),
            $this->getFormOptions(),
            $this->getGroups(),
            $this->getResources(),
            $this->getFields(),
            $this->getAssociations(),
            $this->getActions() ?? []
        );
    }
}
