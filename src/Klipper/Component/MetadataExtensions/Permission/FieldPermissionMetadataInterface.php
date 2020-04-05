<?php

/*
 * This file is part of the Klipper package.
 *
 * (c) François Pluchino <francois.pluchino@klipper.dev>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Klipper\Component\MetadataExtensions\Permission;

/**
 * @author François Pluchino <francois.pluchino@klipper.dev>
 */
interface FieldPermissionMetadataInterface
{
    /**
     * Get the class field name of metadata object field.
     */
    public function getField(): string;

    /**
     * Get the field metadata name.
     */
    public function getName(): string;

    /**
     * Get the field metadata type.
     */
    public function getType(): string;

    /**
     * Get the label.
     */
    public function getLabel(): string;

    /**
     * Get the description.
     */
    public function getDescription(): ?string;

    /**
     * Check if the permissions can be configured for this metadata.
     */
    public function hasEditablePermissions(): bool;

    /**
     * Get the permissions metadatas of the field.
     *
     * @return PermissionMetadataInterface[]
     */
    public function getPermissions(): array;
}
