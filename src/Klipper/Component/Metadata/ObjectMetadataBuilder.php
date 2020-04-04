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
    /**
     * @var null|string
     */
    protected $pluralName;

    /**
     * @var string
     */
    protected $class;

    /**
     * @var FieldMetadataBuilderInterface[]
     */
    protected $fields = [];

    /**
     * @var AssociationMetadataBuilderInterface[]
     */
    protected $associations = [];

    /**
     * @var bool
     */
    protected $sortable = false;

    /**
     * @var null|bool
     */
    protected $multiSortable;

    /**
     * @var null|array
     */
    protected $defaultSortable;

    /**
     * @var null|string[]
     */
    protected $availableContexts;

    /**
     * @var null|string
     */
    protected $fieldIdentifier;

    /**
     * @var null|string
     */
    protected $fieldLabel;

    /**
     * @var null|ActionMetadataBuilderInterface[]
     */
    protected $actions;

    /**
     * @var null|bool
     */
    protected $buildDefaultActions;

    /**
     * @var null|ActionMetadataBuilderInterface
     */
    protected $defaultAction;

    /**
     * @var ResourceInterface[]
     */
    protected $resources = [];

    /**
     * Constructor.
     *
     * @param string $class The class name
     */
    public function __construct(string $class)
    {
        parent::__construct('object');

        $this->class = $class;
    }

    /**
     * {@inheritdoc}
     */
    public function setPluralName(?string $name): self
    {
        $this->pluralName = $name;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getPluralName(): ?string
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

    /**
     * {@inheritdoc}
     */
    public function hasField(string $field): bool
    {
        return isset($this->fields[$field]);
    }

    /**
     * {@inheritdoc}
     */
    public function getField(string $field): ?FieldMetadataBuilderInterface
    {
        return $this->fields[$field] ?? null;
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

    /**
     * {@inheritdoc}
     */
    public function hasAssociation(string $association): bool
    {
        return isset($this->associations[$association]);
    }

    /**
     * {@inheritdoc}
     */
    public function getAssociation(string $association): ?AssociationMetadataBuilderInterface
    {
        return $this->associations[$association] ?? null;
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
    public function isSortable(): bool
    {
        return $this->sortable;
    }

    /**
     * {@inheritdoc}
     */
    public function setMultiSortable(?bool $sortable): self
    {
        $this->multiSortable = $sortable;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function isMultiSortable(): ?bool
    {
        return$this->multiSortable;
    }

    /**
     * {@inheritdoc}
     */
    public function setDefaultSortable(?array $map): self
    {
        $this->defaultSortable = $map;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getDefaultSortable(): ?array
    {
        return $this->defaultSortable;
    }

    /**
     * {@inheritdoc}
     */
    public function setAvailableContexts(?array $availableContexts): self
    {
        $this->availableContexts = $availableContexts;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getAvailableContexts(): ?array
    {
        return $this->availableContexts;
    }

    /**
     * {@inheritdoc}
     */
    public function setFieldIdentifier(?string $name): self
    {
        $this->fieldIdentifier = $name;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getFieldIdentifier(): ?string
    {
        return $this->fieldIdentifier;
    }

    /**
     * {@inheritdoc}
     */
    public function setFieldLabel(?string $name): self
    {
        $this->fieldLabel = $name;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getFieldLabel(): ?string
    {
        return $this->fieldLabel;
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

    /**
     * {@inheritdoc}
     */
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

    /**
     * {@inheritdoc}
     */
    public function getActions(): ?array
    {
        return $this->actions;
    }

    /**
     * {@inheritdoc}
     */
    public function getAction(string $action): ?ActionMetadataBuilderInterface
    {
        return $this->actions[$action] ?? null;
    }

    /**
     * {@inheritdoc}
     */
    public function setBuildDefaultActions(?bool $buildDefaultActions): self
    {
        $this->buildDefaultActions = $buildDefaultActions;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getBuildDefaultActions(): ?bool
    {
        return $this->buildDefaultActions;
    }

    /**
     * {@inheritdoc}
     */
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

    /**
     * {@inheritdoc}
     */
    public function getDefaultAction(): ?ActionMetadataBuilderInterface
    {
        return $this->defaultAction;
    }

    /**
     * {@inheritdoc}
     */
    public function getResources(): array
    {
        return array_values($this->resources);
    }

    /**
     * {@inheritdoc}
     */
    public function addResource(ResourceInterface $resource): void
    {
        $key = (string) $resource;

        if (!isset($this->resources[$key])) {
            $this->resources[$key] = $resource;
        }
    }

    /**
     * {@inheritdoc}
     */
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

    /**
     * {@inheritdoc}
     */
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
            $this->getActions() ?? [],
            false !== $this->getBuildDefaultActions()
        );
    }
}
