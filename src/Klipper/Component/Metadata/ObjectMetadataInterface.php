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

use Symfony\Component\Config\Resource\ResourceInterface;

/**
 * @author François Pluchino <francois.pluchino@klipper.dev>
 */
interface ObjectMetadataInterface extends MetadataInterface
{
    /**
     * Get the plural unique name.
     */
    public function getPluralName(): string;

    /**
     * Get the plural label.
     */
    public function getPluralLabel(): ?string;

    /**
     * Get the class name.
     */
    public function getClass(): string;

    /**
     * Get the field metadatas.
     *
     * @return FieldMetadataInterface[]
     */
    public function getFields(): array;

    /**
     * Check if the field metadata is present.
     *
     * @param string $name The field name
     */
    public function hasField(string $name): bool;

    /**
     * Check if the field metadata is present with the unique field name.
     *
     * @param string $name The unique field name
     */
    public function hasFieldByName(string $name): bool;

    /**
     * Get the field metadata.
     *
     * @param string $name The field name
     */
    public function getField(string $name): FieldMetadataInterface;

    /**
     * Get the field metadata with the unique field name.
     *
     * @param string $name The unique field name
     */
    public function getFieldByName(string $name): FieldMetadataInterface;

    /**
     * Get the association metadatas.
     *
     * @return AssociationMetadataInterface[]
     */
    public function getAssociations(): array;

    /**
     * Check if the association metadata is present.
     *
     * @param string $name The association name
     */
    public function hasAssociation(string $name): bool;

    /**
     * Check if the association metadata is present with the unique association name.
     *
     * @param string $name The unique association name
     */
    public function hasAssociationByName(string $name): bool;

    /**
     * Get the association metadata.
     *
     * @param string $name The association name
     */
    public function getAssociation(string $name): AssociationMetadataInterface;

    /**
     * Get the association metadata with the unique association name.
     *
     * @param string $name The unique association name
     */
    public function getAssociationByName(string $name): AssociationMetadataInterface;

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
     * Get the paths to allow the search on fields defined in deep associations.
     *
     * @return string[]
     */
    public function getDeepSearchPaths(): array;

    /**
     * Get the available actions.
     *
     * @return ActionMetadataInterface[]
     */
    public function getActions(): array;

    /**
     * Check if the action metadata is present.
     *
     * @param string $action The action name
     */
    public function hasAction(string $action): bool;

    /**
     * Get the action.
     *
     * @param string $action The action name
     */
    public function getAction(string $action): ActionMetadataInterface;

    /**
     * Returns an array of resources loaded to build this metadata.
     *
     * @return ResourceInterface[] An array of resources
     */
    public function getResources(): array;
}
