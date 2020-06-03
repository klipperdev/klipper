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
class PermissionMetadata implements PermissionMetadataInterface
{
    protected PermissionInterface $permission;

    protected bool $granted;

    protected bool $locked;

    protected string $label;

    protected ?string $description;

    /**
     * @param PermissionInterface $permission  The permission
     * @param bool                $granted     Check if the permission is granted
     * @param bool                $locked      Check if the permission is locked
     * @param string              $label       The permission label
     * @param null|string         $description The permission description
     */
    public function __construct(PermissionInterface $permission, bool $granted, bool $locked, string $label, ?string $description = null)
    {
        $this->permission = $permission;
        $this->granted = $granted;
        $this->locked = $locked;
        $this->label = $label;
        $this->description = $description;
    }

    public function getPermission(): PermissionInterface
    {
        return $this->permission;
    }

    public function getOperation(): string
    {
        return $this->permission->getOperation();
    }

    public function getContexts(): array
    {
        return $this->permission->getContexts();
    }

    public function getLabel(): string
    {
        return $this->label;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function isGranted(): bool
    {
        return $this->granted;
    }

    public function isLocked(): bool
    {
        return $this->locked;
    }
}
