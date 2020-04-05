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
    /**
     * @var ViewAssociationMetadataInterface
     */
    protected $metadata;

    /**
     * @var PermissionMetadataInterface[]
     */
    protected $permissions;

    /**
     * Constructor.
     *
     * @param ViewAssociationMetadataInterface $metadata    The view association metadata
     * @param PermissionMetadataInterface[]    $permissions The association permissions
     */
    public function __construct(ViewAssociationMetadataInterface $metadata, array $permissions)
    {
        $this->metadata = $metadata;
        $this->permissions = $permissions;
    }

    /**
     * {@inheritdoc}
     */
    public function getAssociation(): string
    {
        return $this->metadata->getAssociation();
    }

    /**
     * {@inheritdoc}
     */
    public function getName(): string
    {
        return $this->metadata->getName();
    }

    /**
     * {@inheritdoc}
     */
    public function getType(): string
    {
        return $this->metadata->getType();
    }

    /**
     * {@inheritdoc}
     */
    public function getTarget(): string
    {
        return $this->metadata->getTarget();
    }

    /**
     * {@inheritdoc}
     */
    public function getLabel(): string
    {
        return $this->metadata->getLabel();
    }

    /**
     * {@inheritdoc}
     */
    public function getDescription(): ?string
    {
        return $this->metadata->getDescription();
    }

    /**
     * {@inheritdoc}
     */
    public function hasEditablePermissions(): bool
    {
        return $this->metadata->hasEditablePermissions();
    }

    /**
     * {@inheritdoc}
     */
    public function getPermissions(): array
    {
        return $this->permissions;
    }
}
