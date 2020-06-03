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
class ViewFieldMetadata extends BaseViewChildMetadata implements ViewFieldMetadataInterface
{
    /**
     * @var string
     */
    protected $field;

    /**
     * @var bool
     */
    protected $sortable;

    /**
     * @var bool
     */
    protected $filterable;

    /**
     * @var bool
     */
    protected $searchable;

    /**
     * @var bool
     */
    protected $translatable;

    /**
     * @param string      $field               The field name in class
     * @param string      $name                The name of metadata field
     * @param string      $type                The type of metadata field
     * @param string      $label               The label of metadata field
     * @param null|string $description         The description of metadata field
     * @param bool        $sortable            Check if the metadata is sortable
     * @param bool        $filterable          Check if the metadata is filterable
     * @param bool        $searchable          Check if the metadata is searchable
     * @param bool        $translatable        Check if the metadata is translatable
     * @param bool        $readOnly            Check if the metadata is read only
     * @param bool        $required            Check if the metadata is required
     * @param null|string $input               The input type
     * @param null|array  $inputConfig         The config of input type
     * @param bool        $editablePermissions Check if the permissions can be configured for this metadata
     */
    public function __construct(
        string $field,
        string $name,
        string $type,
        string $label,
        ?string $description,
        bool $sortable,
        bool $filterable,
        bool $searchable,
        bool $translatable,
        bool $readOnly,
        bool $required,
        ?string $input,
        ?array $inputConfig,
        bool $editablePermissions = false
    ) {
        parent::__construct($name, $type, $label, $description, $readOnly, $required, $input, $inputConfig, $editablePermissions);

        $this->field = $field;
        $this->sortable = $sortable;
        $this->filterable = $filterable;
        $this->searchable = $searchable;
        $this->translatable = $translatable;
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
