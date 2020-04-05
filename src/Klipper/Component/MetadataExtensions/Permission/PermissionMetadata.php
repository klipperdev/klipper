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
    /**
     * @var PermissionInterface
     */
    protected $permission;

    /**
     * @var bool
     */
    protected $granted;

    /**
     * @var bool
     */
    protected $locked;

    /**
     * @var string
     */
    protected $label;

    /**
     * @var null|string
     */
    protected $description;

    /**
     * Constructor.
     *
     * @param PermissionInterface $permission  The permission
     * @param bool                $granted     Check if the permission is granted
     * @param bool                $locked      Check if the permission is locked
     * @param string              $label       The permission label
     * @param null|string         $description The permission description
     */
    public function __construct(PermissionInterface $permission, $granted, $locked, $label, $description = null)
    {
        $this->permission = $permission;
        $this->granted = $granted;
        $this->locked = $locked;
        $this->label = $label;
        $this->description = $description;
    }

    /**
     * {@inheritdoc}
     */
    public function getPermission(): PermissionInterface
    {
        return $this->permission;
    }

    /**
     * {@inheritdoc}
     */
    public function getOperation(): string
    {
        return $this->permission->getOperation();
    }

    /**
     * {@inheritdoc}
     */
    public function getContexts(): array
    {
        return $this->permission->getContexts();
    }

    /**
     * {@inheritdoc}
     */
    public function getLabel(): string
    {
        return $this->label;
    }

    /**
     * {@inheritdoc}
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * {@inheritdoc}
     */
    public function isGranted(): bool
    {
        return $this->granted;
    }

    /**
     * {@inheritdoc}
     */
    public function isLocked(): bool
    {
        return $this->locked;
    }
}
