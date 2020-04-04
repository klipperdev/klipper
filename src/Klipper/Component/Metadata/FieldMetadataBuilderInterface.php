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
interface FieldMetadataBuilderInterface extends ChildMetadataBuilderInterface
{
    /**
     * Get the field name.
     */
    public function getField(): string;

    /**
     * Set if the field is sortable.
     *
     * @param bool $sortable Check if the fields is sortable
     *
     * @return static
     */
    public function setSortable(?bool $sortable);

    /**
     * Check if the field is sortable.
     *
     * @return bool
     */
    public function isSortable(): ?bool;

    /**
     * Set if the field is filterable.
     *
     * @param bool $filterable Check if the fields is filterable
     *
     * @return static
     */
    public function setFilterable(?bool $filterable);

    /**
     * Check if the field is filterable.
     *
     * @return bool
     */
    public function isFilterable(): ?bool;

    /**
     * Set if the field is searchable.
     *
     * @param bool $searchable Check if the fields is searchable
     *
     * @return static
     */
    public function setSearchable(?bool $searchable);

    /**
     * Check if the field is searchable.
     *
     * @return bool
     */
    public function isSearchable(): ?bool;

    /**
     * Set if the field is translatable.
     *
     * @param bool $translatable Check if the fields is translatable
     *
     * @return static
     */
    public function setTranslatable(?bool $translatable);

    /**
     * Check if the field is translatable.
     *
     * @return bool
     */
    public function isTranslatable(): ?bool;

    /**
     * Merge the field metadata builder.
     *
     * @param FieldMetadataBuilderInterface $builder The new field metadata builder
     *
     * @return static
     */
    public function merge(FieldMetadataBuilderInterface $builder);

    /**
     * Build the field metadata.
     *
     * @param ObjectMetadataInterface $parent The object metadata parent
     */
    public function build(ObjectMetadataInterface $parent): FieldMetadataInterface;
}
