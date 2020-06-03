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
     * @var
     */
    protected ViewMetadataInterface $metadata;

    /**
     * @var PermissionMetadataInterface[]
     */
    protected array $permissions;

    /**
     * @var null|FieldPermissionMetadataInterface[]
     */
    protected ?array $fields;

    /**
     * @var null|AssociationPermissionMetadataInterface[]
     */
    protected ?array $associations;

    /**
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

    public function getClass(): string
    {
        return $this->metadata->getClass();
    }

    public function getName(): string
    {
        return $this->metadata->getName();
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

    public function hasField(string $name): bool
    {
        return isset($this->fields[$name]);
    }

    public function getField(string $name): FieldPermissionMetadataInterface
    {
        if (!$this->hasField($name)) {
            throw new FieldMetadataNotFoundException($name);
        }

        return $this->fields[$name];
    }

    public function getFields(): array
    {
        return $this->fields ?? [];
    }

    public function hasAssociation(string $name): bool
    {
        return isset($this->associations[$name]);
    }

    public function getAssociation(string $name): AssociationPermissionMetadataInterface
    {
        if (!$this->hasAssociation($name)) {
            throw new AssociationMetadataNotFoundException($name);
        }

        return $this->associations[$name];
    }

    public function getAssociations(): array
    {
        return $this->associations ?? [];
    }
}
