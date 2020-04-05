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

use Klipper\Component\Security\Model\PermissionInterface;

/**
 * @author François Pluchino <francois.pluchino@klipper.dev>
 */
interface PermissionMetadataInterface
{
    /**
     * Get the permission.
     */
    public function getPermission(): PermissionInterface;

    /**
     * Get the permission operation.
     */
    public function getOperation(): string;

    /**
     * Get the permission contexts.
     *
     * @return string[]
     */
    public function getContexts(): array;

    /**
     * Get the permission label.
     */
    public function getLabel(): string;

    /**
     * Get the permission description.
     *
     * @return string
     */
    public function getDescription(): ?string;

    /**
     * Check if the permission is granted.
     */
    public function isGranted(): bool;

    /**
     * Check if the permission is locked.
     */
    public function isLocked(): bool;
}
