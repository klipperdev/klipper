<?php

/*
 * This file is part of the Klipper package.
 *
 * (c) François Pluchino <francois.pluchino@klipper.dev>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Klipper\Component\Metadata\View;

/**
 * @author François Pluchino <francois.pluchino@klipper.dev>
 */
class ViewMetadata implements ViewMetadataInterface
{
    protected string $class;

    protected string $name;

    protected string $pluralName;

    protected string $label;

    protected string $pluralLabel;

    protected ?string $description;

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
     * @var string[]
     */
    protected array $deepSearchPaths;

    protected bool $editablePermissions;

    protected bool $master = true;

    /**
     * @var null|string[]
     */
    protected ?array $availableActions = null;

    /**
     * @var null|ViewFieldMetadataInterface[]
     */
    protected ?array $fields = null;

    /**
     * @var null|ViewAssociationMetadataInterface[]
     */
    protected ?array $associations = null;

    /**
     * @param string                                  $class               The class name
     * @param string                                  $name                The name of metadata object
     * @param string                                  $pluralName          The plural name of metadata object
     * @param string                                  $fieldIdentifier     The name of the field used for identifier
     * @param string                                  $fieldLabel          The name of field used for label
     * @param string                                  $label               The label of metadata object
     * @param string                                  $pluralLabel         The plural label of metadata object
     * @param null|string                             $description         The description of metadata object
     * @param bool                                    $multiSortable       Check if the metadata is multi sortable
     * @param array                                   $defaultSortable     The default sortable
     * @param string[]                                $availableContexts   The available contexts
     * @param string[]                                $deepSearchPaths     The deep search paths
     * @param bool                                    $editablePermissions Check if the permissions can be configured for this metadata
     * @param null|string[]                           $availableActions    The available actions
     * @param null|ViewFieldMetadataInterface[]       $fields              The field metadatas
     * @param null|ViewAssociationMetadataInterface[] $associations        The association metadatas
     */
    public function __construct(
        string $class,
        string $name,
        string $pluralName,
        string $fieldIdentifier,
        string $fieldLabel,
        string $label,
        string $pluralLabel,
        ?string $description = null,
        bool $multiSortable = false,
        array $defaultSortable = [],
        array $availableContexts = [],
        array $deepSearchPaths = [],
        bool $editablePermissions = false,
        ?array $availableActions = null,
        ?array $fields = null,
        ?array $associations = null
    ) {
        $this->class = $class;
        $this->name = $name;
        $this->pluralName = $pluralName;
        $this->label = $label;
        $this->pluralLabel = $pluralLabel;
        $this->description = $description;
        $this->multiSortable = $multiSortable;
        $this->defaultSortable = $defaultSortable;
        $this->availableContexts = $availableContexts;
        $this->fieldIdentifier = $fieldIdentifier;
        $this->fieldLabel = $fieldLabel;
        $this->deepSearchPaths = $deepSearchPaths;
        $this->editablePermissions = $editablePermissions;
        $this->availableActions = $availableActions;
        $this->searchable = !empty($deepSearchPaths);

        if (\is_array($fields)) {
            foreach ($fields as $field) {
                $this->fields[] = $field;

                if ($field->isSortable()) {
                    $this->sortable = true;
                }

                if ($field->isFilterable()) {
                    $this->filterable = true;
                }

                if ($field->isSearchable()) {
                    $this->searchable = true;
                }

                if ($field->isTranslatable()) {
                    $this->translatable = true;
                }
            }
        }

        if (\is_array($associations)) {
            foreach ($associations as $association) {
                $this->associations[] = $association;

                if ($association->isMasterDetails()) {
                    $this->master = false;
                }
            }
        }
    }

    public function getClass(): string
    {
        return $this->class;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getPluralName(): string
    {
        return $this->pluralName;
    }

    public function getLabel(): string
    {
        return $this->label;
    }

    public function getPluralLabel(): string
    {
        return $this->pluralLabel;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function isSortable(): bool
    {
        return $this->sortable;
    }

    public function isMultiSortable(): bool
    {
        return $this->multiSortable;
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

    public function isTranslatable(): bool
    {
        return $this->translatable;
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

    public function getDeepSearchPaths(): array
    {
        return $this->deepSearchPaths;
    }

    public function hasEditablePermissions(): bool
    {
        return $this->editablePermissions;
    }

    public function isMaster(): bool
    {
        return $this->master;
    }

    public function getAvailableActions(): array
    {
        return $this->availableActions ?? [];
    }

    public function getFields(): array
    {
        return $this->fields ?? [];
    }

    public function getAssociations(): array
    {
        return $this->associations ?? [];
    }
}
