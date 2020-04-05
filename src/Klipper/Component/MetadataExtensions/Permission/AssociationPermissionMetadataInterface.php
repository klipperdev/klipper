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
interface AssociationPermissionMetadataInterface
{
    /**
     * Get the class association name of metadata object association.
     */
    public function getAssociation(): string;

    /**
     * Get the association metadata name.
     */
    public function getName(): string;

    /**
     * Get the association metadata type.
     */
    public function getType(): string;

    /**
     * Get the association metadata target.
     */
    public function getTarget(): string;

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
     * Get the permissions metadatas of the association.
     *
     * @return PermissionMetadataInterface[]
     */
    public function getPermissions(): array;
}
