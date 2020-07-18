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
interface ViewMetadataInterface
{
    /**
     * Get the class name of metadata object.
     */
    public function getClass(): string;

    /**
     * Get the name of metadata object.
     */
    public function getName(): string;

    /**
     * Get the plural name of metadata object.
     */
    public function getPluralName(): string;

    /**
     * Get the label of metadata object.
     */
    public function getLabel(): string;

    /**
     * Get the description of metadata object.
     */
    public function getDescription(): ?string;

    /**
     * Check if the object is sortable.
     */
    public function isSortable(): bool;

    /**
     * Check if the object is sortable with multiple fields.
     */
    public function isMultiSortable(): bool;

    /**
     * Get the map of default sortable with field and direction.
     */
    public function getDefaultSortable(): array;

    /**
     * Check if the object is filterable.
     */
    public function isFilterable(): bool;

    /**
     * Check if the object is searchable.
     */
    public function isSearchable(): bool;

    /**
     * Check if the field is translatable.
     */
    public function isTranslatable(): bool;

    /**
     * Get the available contexts.
     *
     * @return string[]
     */
    public function getAvailableContexts(): array;

    /**
     * Get the field name used for the identifier.
     */
    public function getFieldIdentifier(): string;

    /**
     * Get the field name used for the label.
     */
    public function getFieldLabel(): string;

    /**
     * Check if the permissions can be configured for this metadata.
     */
    public function hasEditablePermissions(): bool;

    /**
     * Check if the object is the master or details.
     */
    public function isMaster(): bool;

    /**
     * Get the available actions.
     *
     * @return string[]
     */
    public function getAvailableActions(): array;

    /**
     * Get the field metadatas.
     *
     * @return ViewFieldMetadataInterface[]
     */
    public function getFields(): array;

    /**
     * Get the association metadatas.
     *
     * @return ViewAssociationMetadataInterface[]
     */
    public function getAssociations(): array;
}
