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
interface ObjectMetadataBuilderInterface extends MetadataBuilderInterface
{
    /**
     * Set the plural unique name.
     *
     * @param string $name The unique plural name
     *
     * @return static
     */
    public function setPluralName(?string $name);

    /**
     * Get the unique plural name.
     */
    public function getPluralName(): ?string;

    /**
     * Get the class name.
     */
    public function getClass(): string;

    /**
     * Add the field metadata builder.
     *
     * @param FieldMetadataBuilderInterface $metadata The field metadata builder
     *
     * @return static
     */
    public function addField(FieldMetadataBuilderInterface $metadata);

    /**
     * Check if the field metadata builder is present.
     *
     * @param string $field The field name
     */
    public function hasField(string $field): bool;

    /**
     * Get the field metadata builder.
     *
     * @param string $field The field name
     */
    public function getField(string $field): ?FieldMetadataBuilderInterface;

    /**
     * Get the field metadata builders.
     *
     * @return FieldMetadataBuilderInterface[]
     */
    public function getFields(): array;

    /**
     * Add the association metadata builder.
     *
     * @param AssociationMetadataBuilderInterface $metadata The association metadata builder
     *
     * @return static
     */
    public function addAssociation(AssociationMetadataBuilderInterface $metadata);

    /**
     * Check if the association metadata builder is present.
     *
     * @param string $association The association name
     */
    public function hasAssociation(string $association): bool;

    /**
     * Get the association metadata builder.
     *
     * @param string $association The association name
     */
    public function getAssociation(string $association): ?AssociationMetadataBuilderInterface;

    /**
     * Get the association metadata builders.
     *
     * @return AssociationMetadataBuilderInterface[]
     */
    public function getAssociations(): array;

    /**
     * Check if the object is sortable.
     *
     * @return bool
     */
    public function isSortable(): ?bool;

    /**
     * Set if the field is multi sortable.
     *
     * @param bool $sortable Check if the fields is multi sortable
     *
     * @return static
     */
    public function setMultiSortable(?bool $sortable);

    /**
     * Check if the object is sortable with multiple fields.
     *
     * @return bool
     */
    public function isMultiSortable(): ?bool;

    /**
     * Set if the default sortable config.
     *
     * @param array $map The map of default sortable with field and direction
     *
     * @return static
     */
    public function setDefaultSortable(?array $map);

    /**
     * Get the map of default sortable with field and direction.
     *
     * @return array
     */
    public function getDefaultSortable(): ?array;

    /**
     * Set the available contexts.
     *
     * @param string[] $availableContexts
     *
     * @return static
     */
    public function setAvailableContexts(?array $availableContexts);

    /**
     * Get the available contexts.
     *
     * @return string[]
     */
    public function getAvailableContexts(): ?array;

    /**
     * Set the field name used for the identifier.
     *
     * @param null|string $name The field name
     *
     * @return static
     */
    public function setFieldIdentifier(?string $name);

    /**
     * Get the field name used for the identifier.
     *
     * @return string
     */
    public function getFieldIdentifier(): ?string;

    /**
     * Set the field name used for the label.
     *
     * @param null|string $name The field name
     *
     * @return static
     */
    public function setFieldLabel(?string $name);

    /**
     * Get the field name used for the label.
     *
     * @return string
     */
    public function getFieldLabel(): ?string;

    /**
     * Check if the action metadata builder is present.
     *
     * @param string $action The action name
     */
    public function hasAction(string $action): bool;

    /**
     * Set the action metadatas.
     *
     * @param ActionMetadataBuilderInterface[] $actions The action metadatas
     *
     * @return static
     */
    public function setActions(?array $actions);

    /**
     * Add the action metadata.
     *
     * @param ActionMetadataBuilderInterface $action The action metadata builder
     *
     * @return static
     */
    public function addAction(ActionMetadataBuilderInterface $action);

    /**
     * Get the available actions.
     *
     * @return null|ActionMetadataBuilderInterface[]
     */
    public function getActions(): ?array;

    /**
     * Get the action.
     *
     * @param string $action The action name
     */
    public function getAction(string $action): ?ActionMetadataBuilderInterface;

    /**
     * Define if the default actions must be built even if the actions is manually defined.
     *
     * @param null|bool $buildDefaultActions check if the default actions must be built
     *
     * @return static
     */
    public function setBuildDefaultActions(?bool $buildDefaultActions);

    /**
     * Check if the default actions must be built even if the actions is manually defined.
     */
    public function getBuildDefaultActions(): ?bool;

    /**
     * Set the default values of actions.
     *
     * @param null|ActionMetadataBuilderInterface $action The default action
     *
     * @return static
     */
    public function setDefaultAction(?ActionMetadataBuilderInterface $action);

    public function getDefaultAction(): ?ActionMetadataBuilderInterface;

    /**
     * Returns an array of resources loaded to build this metadata.
     *
     * @return ResourceInterface[] An array of resources
     */
    public function getResources(): array;

    /**
     * Adds a resource for this metadata. If the resource already exists
     * it is not added.
     *
     * @param ResourceInterface $resource The resource instance
     */
    public function addResource(ResourceInterface $resource);

    /**
     * Merge the object metadata builder.
     *
     * @param ObjectMetadataBuilderInterface $builder The new object metadata builder
     *
     * @return static
     */
    public function merge(ObjectMetadataBuilderInterface $builder);

    /**
     * Build the object metadata.
     */
    public function build(): ObjectMetadataInterface;
}
