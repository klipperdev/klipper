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
use Klipper\Component\Metadata\Exception\FieldMetadataNotFoundException;
use Klipper\Component\Metadata\View\ViewMetadataInterface;

/**
 * @author François Pluchino <francois.pluchino@klipper.dev>
 */
class ObjectPermissionMetadata implements ObjectPermissionMetadataInterface
{
    /**
     * @var ViewMetadataInterface
     */
    protected $metadata;

    /**
     * @var PermissionMetadataInterface[]
     */
    protected $permissions;

    /**
     * @var null|FieldPermissionMetadataInterface[]
     */
    protected $fields;

    /**
     * @var null|AssociationPermissionMetadataInterface[]
     */
    protected $associations;

    /**
     * Constructor.
     *
     * @param ViewMetadataInterface                    $metadata     The view metadata of object
     * @param PermissionMetadataInterface[]            $permissions  The object permissions
     * @param FieldPermissionMetadataInterface[]       $fields       The field permission metadatas
     * @param AssociationPermissionMetadataInterface[] $associations The association permission metadatas
     */
    public function __construct(
        ViewMetadataInterface $metadata,
        array $permissions,
        array $fields,
        array $associations
    ) {
        $this->metadata = $metadata;
        $this->permissions = $permissions;
        $this->fields = !empty($fields) ? $fields : null;
        $this->associations = !empty($associations) ? $associations : null;
    }

    /**
     * {@inheritdoc}
     */
    public function getClass(): string
    {
        return $this->metadata->getClass();
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

    /**
     * {@inheritdoc}
     */
    public function hasField(string $name): bool
    {
        return isset($this->fields[$name]);
    }

    /**
     * {@inheritdoc}
     */
    public function getField(string $name): FieldPermissionMetadataInterface
    {
        if (!$this->hasField($name)) {
            throw new FieldMetadataNotFoundException($name);
        }

        return $this->fields[$name];
    }

    /**
     * {@inheritdoc}
     */
    public function getFields(): array
    {
        return $this->fields ?? [];
    }

    /**
     * {@inheritdoc}
     */
    public function hasAssociation(string $name): bool
    {
        return isset($this->associations[$name]);
    }

    /**
     * {@inheritdoc}
     */
    public function getAssociation(string $name): AssociationPermissionMetadataInterface
    {
        if (!$this->hasAssociation($name)) {
            throw new AssociationMetadataNotFoundException($name);
        }

        return $this->associations[$name];
    }

    /**
     * {@inheritdoc}
     */
    public function getAssociations(): array
    {
        return $this->associations ?? [];
    }
}
