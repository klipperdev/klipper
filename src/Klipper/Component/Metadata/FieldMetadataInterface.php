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
interface FieldMetadataInterface extends ChildMetadataInterface
{
    /**
     * Get the field name.
     */
    public function getField(): string;

    /**
     * Check if the field is sortable.
     */
    public function isSortable(): bool;

    /**
     * Check if the field is filterable.
     */
    public function isFilterable(): bool;

    /**
     * Check if the field is searchable.
     */
    public function isSearchable(): bool;

    /**
     * Check if the field is translatable.
     */
    public function isTranslatable(): bool;
}
