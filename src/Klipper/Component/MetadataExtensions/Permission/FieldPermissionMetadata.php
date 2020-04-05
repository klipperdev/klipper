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

use Klipper\Component\Metadata\View\ViewFieldMetadataInterface;

/**
 * @author François Pluchino <francois.pluchino@klipper.dev>
 */
class FieldPermissionMetadata implements FieldPermissionMetadataInterface
{
    /**
     * @var ViewFieldMetadataInterface
     */
    protected $metadata;

    /**
     * @var PermissionMetadataInterface[]
     */
    protected $permissions;

    /**
     * Constructor.
     *
     * @param ViewFieldMetadataInterface    $metadata    The view field metadata
     * @param PermissionMetadataInterface[] $permissions The field permissions
     */
    public function __construct(ViewFieldMetadataInterface $metadata, array $permissions)
    {
        $this->metadata = $metadata;
        $this->permissions = $permissions;
    }

    /**
     * {@inheritdoc}
     */
    public function getField(): string
    {
        return $this->metadata->getField();
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
