<?php

/*
 * This file is part of the Klipper package.
 *
 * (c) François Pluchino <francois.pluchino@klipper.dev>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Klipper\Component\Security\Model;

/**
 * Permission checking.
 *
 * @author François Pluchino <francois.pluchino@klipper.dev>
 */
class PermissionChecking
{
    protected PermissionInterface $permission;

    protected bool $granted;

    protected bool $locked;

    /**
     * @param PermissionInterface $permission The permission
     * @param bool                $granted    Check if the permission is granted
     * @param bool                $locked     Check if the permission is locked
     */
    public function __construct(PermissionInterface $permission, bool $granted, bool $locked = false)
    {
        $this->permission = $permission;
        $this->granted = $granted;
        $this->locked = $locked;
    }

    /**
     * Get the permission.
     */
    public function getPermission(): PermissionInterface
    {
        return $this->permission;
    }

    /**
     * Check if the permission is granted.
     */
    public function isGranted(): bool
    {
        return $this->granted;
    }

    /**
     * Check if the permission is locked.
     */
    public function isLocked(): bool
    {
        return $this->locked;
    }
}
