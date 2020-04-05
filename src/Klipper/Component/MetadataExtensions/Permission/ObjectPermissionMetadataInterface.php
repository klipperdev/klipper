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

use Klipper\Component\Metadata\Exception\AssociationMetadataNotFoundException;

/**
 * @author François Pluchino <francois.pluchino@klipper.dev>
 */
interface ObjectPermissionMetadataInterface
{
    /**
     * Get the class name of metadata.
     */
    public function getClass(): string;

    /**
     * Get the object metadata name.
     */
    public function getName(): string;

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
     * Get the permissions metadatas of the object.
     *
     * @return PermissionMetadataInterface[]
     */
    public function getPermissions(): array;

    /**
     * Check if the the field metadata is present.
     *
     * @param string $name The field name
     */
    public function hasField(string $name): bool;

    /**
     * Get the field metadata.
     *
     * @param string $name The field name
     *
     * @throws AssociationMetadataNotFoundException When the field does not exist
     */
    public function getField(string $name): FieldPermissionMetadataInterface;

    /**
     * Get the field metadatas.
     *
     * @return FieldPermissionMetadataInterface[]
     */
    public function getFields(): array;

    /**
     * Check if the the association metadata is present.
     *
     * @param string $name The association name
     */
    public function hasAssociation(string $name): bool;

    /**
     * Get the association metadata.
     *
     * @param string $name The association name
     *
     * @throws AssociationMetadataNotFoundException When the association does not exist
     */
    public function getAssociation(string $name): AssociationPermissionMetadataInterface;

    /**
     * Get the association metadatas.
     *
     * @return AssociationPermissionMetadataInterface[]
     */
    public function getAssociations(): array;
}
