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

use Klipper\Component\Metadata\Exception\ChoiceNotFoundException;
use Klipper\Component\Metadata\Exception\ObjectMetadataNotFoundException;
use Klipper\Component\Metadata\View\ViewChoiceInterface;
use Klipper\Component\Metadata\View\ViewMetadataInterface;
use Klipper\Component\Security\Model\PermissionInterface;
use Klipper\Component\Security\Model\RoleInterface;

/**
 * @author François Pluchino <francois.pluchino@klipper.dev>
 */
interface PermissionMetadataManagerInterface
{
    /**
     * * Clear all permission caches.
     */
    public function clear(): void;

    /**
     * Get the list of metadatas.
     *
     * @param bool $onlyPermission Check if the list must contain only the metadatas managed by the permission manager
     *
     * @return ViewMetadataInterface[]
     */
    public function getMetadatas(bool $onlyPermission = false): array;

    /**
     * Check if the metadata exists and is managed by the permission manager.
     *
     * @param string $name               The object name
     * @param bool   $requiredPermission Check if the metadata must be managed by the permission manager
     */
    public function hasMetadata(string $name, bool $requiredPermission = false): bool;

    /**
     * Get the metadata.
     *
     * @param string $name               The object name
     * @param bool   $requiredPermission Check if the metadata must be managed by the permission manager
     *
     * @throws ObjectMetadataNotFoundException When the metadata is not found or is not managed by the permission manager
     */
    public function getMetadata(string $name, bool $requiredPermission = false): ViewMetadataInterface;

    /**
     * Get the choices.
     *
     * @return ViewChoiceInterface[]
     */
    public function getChoices(): array;

    /**
     * Check if the choice exist.
     *
     * @param string $name The choice name
     */
    public function hasChoice(string $name): bool;

    /**
     * Get the choice.
     *
     * @param string $name The choice name
     *
     * @throws ChoiceNotFoundException When the choice is not found
     */
    public function getChoice(string $name): ViewChoiceInterface;

    /**
     * Get the permission metadatas of role.
     *
     * @param RoleInterface $role The role
     *
     * @return PermissionMetadataInterface[]
     */
    public function getPermissions(RoleInterface $role): array;

    /**
     * Get the object permission metadata.
     *
     * @param RoleInterface $role               The role
     * @param string        $object             The object name
     * @param bool          $requiredPermission Check if the metadata must be managed by the permission manager
     *
     * @throws ObjectMetadataNotFoundException When the metadata is not found or is not managed by the permission manager
     */
    public function getObjectPermissions(RoleInterface $role, string $object, bool $requiredPermission = false): ObjectPermissionMetadataInterface;

    /**
     * Build the permission metadata.
     *
     * @param PermissionInterface $permission The permission
     * @param bool                $granted    Check if the permission is granted
     * @param bool                $locked     Check if the permission is locked
     */
    public function buildPermission(PermissionInterface $permission, bool $granted = true, bool $locked = false): PermissionMetadataInterface;

    /**
     * Build the permission metadatas.
     *
     * @param \ArrayAccess|PermissionInterface[] $permissions The permissions
     * @param bool                               $granted     Check if the permission is granted
     * @param bool                               $locked      Check if the permission is locked
     *
     * @return PermissionMetadataInterface[]
     */
    public function buildPermissions($permissions, bool $granted = true, bool $locked = false): array;
}
