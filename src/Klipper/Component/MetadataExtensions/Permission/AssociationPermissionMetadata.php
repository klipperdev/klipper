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

use Klipper\Component\Metadata\View\ViewAssociationMetadataInterface;

/**
 * @author François Pluchino <francois.pluchino@klipper.dev>
 */
class AssociationPermissionMetadata implements AssociationPermissionMetadataInterface
{
    protected ViewAssociationMetadataInterface $metadata;

    /**
     * @var PermissionMetadataInterface[]
     */
    protected array $permissions;

    /**
     * @param ViewAssociationMetadataInterface $metadata    The view association metadata
     * @param PermissionMetadataInterface[]    $permissions The association permissions
     */
    public function __construct(ViewAssociationMetadataInterface $metadata, array $permissions)
    {
        $this->metadata = $metadata;
        $this->permissions = $permissions;
    }

    public function getAssociation(): string
    {
        return $this->metadata->getAssociation();
    }

    public function getName(): string
    {
        return $this->metadata->getName();
    }

    public function getType(): string
    {
        return $this->metadata->getType();
    }

    public function getTarget(): string
    {
        return $this->metadata->getTarget();
    }

    public function getLabel(): string
    {
        return $this->metadata->getLabel();
    }

    public function getDescription(): ?string
    {
        return $this->metadata->getDescription();
    }

    public function hasEditablePermissions(): bool
    {
        return $this->metadata->hasEditablePermissions();
    }

    public function getPermissions(): array
    {
        return $this->permissions;
    }
}
