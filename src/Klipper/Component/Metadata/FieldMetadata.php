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

/**
 * @author François Pluchino <francois.pluchino@klipper.dev>
 */
class FieldMetadata extends BaseChildMetadata implements FieldMetadataInterface
{
    protected string $field;

    protected bool $sortable;

    protected bool $filterable;

    protected bool $searchable;

    protected bool $translatable;

    public function __construct(
        ObjectMetadataInterface $parent,
        string $field,
        string $name,
        string $type,
        string $label,
        ?string $description,
        ?string $translationDomain,
        bool $public,
        bool $sortable,
        bool $filterable,
        bool $searchable,
        bool $translatable,
        bool $readOnly,
        bool $required,
        ?string $input,
        ?array $inputConfig,
        ?string $formType,
        array $formOptions,
        array $groups
    ) {
        parent::__construct(
            $name,
            $type,
            $label,
            $description,
            $translationDomain,
            $public
        );

        $this->parent = $parent;
        $this->field = $field;
        $this->sortable = $sortable;
        $this->filterable = $filterable;
        $this->searchable = $searchable;
        $this->translatable = $translatable;
        $this->readOnly = $readOnly;
        $this->required = $required;
        $this->input = $input;
        $this->inputConfig = $inputConfig;
        $this->formType = $formType;
        $this->formOptions = $formOptions;
        $this->groups = $groups;
    }

    public function getField(): string
    {
        return $this->field;
    }

    public function isSortable(): bool
    {
        return $this->sortable;
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
}
