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

use Klipper\Component\Metadata\AssociationMetadataInterface;
use Klipper\Component\Metadata\Exception\ChoiceNotFoundException;
use Klipper\Component\Metadata\Exception\ObjectMetadataNotFoundException;
use Klipper\Component\Metadata\FieldMetadataInterface;
use Klipper\Component\Metadata\MetadataManagerInterface;
use Klipper\Component\Metadata\ObjectMetadataInterface;
use Klipper\Component\Metadata\Util\ChoiceUtil;
use Klipper\Component\Metadata\Util\MetadataUtil;
use Klipper\Component\Metadata\View\ViewAssociationMetadata;
use Klipper\Component\Metadata\View\ViewAssociationMetadataInterface;
use Klipper\Component\Metadata\View\ViewChoice;
use Klipper\Component\Metadata\View\ViewChoiceInterface;
use Klipper\Component\Metadata\View\ViewFieldMetadata;
use Klipper\Component\Metadata\View\ViewFieldMetadataInterface;
use Klipper\Component\Metadata\View\ViewMetadata;
use Klipper\Component\Metadata\View\ViewMetadataInterface;
use Klipper\Component\Security\Identity\SubjectIdentityInterface;
use Klipper\Component\Security\Model\PermissionInterface;
use Klipper\Component\Security\Model\RoleInterface;
use Klipper\Component\Security\Permission\FieldVote;
use Klipper\Component\Security\Permission\PermissionManagerInterface;
use Klipper\Component\Security\Permission\PermVote;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * @author François Pluchino <francois.pluchino@klipper.dev>
 */
class PermissionMetadataManager implements PermissionMetadataManagerInterface
{
    protected MetadataManagerInterface $metadataManager;

    protected PermissionManagerInterface $permissionManager;

    protected TranslatorInterface $translator;

    protected AuthorizationCheckerInterface $authChecker;

    /**
     * @param MetadataManagerInterface      $metadataManager   The metadata manager
     * @param PermissionManagerInterface    $permissionManager The permission manager
     * @param TranslatorInterface           $translator        The translator
     * @param AuthorizationCheckerInterface $authChecker       The authorization checker
     */
    public function __construct(
        MetadataManagerInterface $metadataManager,
        PermissionManagerInterface $permissionManager,
        TranslatorInterface $translator,
        AuthorizationCheckerInterface $authChecker
    ) {
        $this->metadataManager = $metadataManager;
        $this->permissionManager = $permissionManager;
        $this->translator = $translator;
        $this->authChecker = $authChecker;
    }

    public function clear(): void
    {
        $this->permissionManager->clear();
    }

    public function getMetadatas(bool $onlyPermission = false): array
    {
        $metadatas = [];

        foreach ($this->metadataManager->all() as $metadata) {
            if (!$onlyPermission || ($onlyPermission && $this->permissionManager->hasConfig($metadata->getClass()))) {
                $viewMeta = $this->buildObjectMetadata($metadata);

                if (null !== $viewMeta) {
                    $metadatas[$metadata->getName()] = $viewMeta;
                }
            }
        }

        usort($metadatas, [$this, 'sortMetadatas']);

        return $metadatas;
    }

    public function hasMetadata(string $name, bool $requiredPermission = false): bool
    {
        if ($this->metadataManager->hasByName($name)) {
            $meta = $this->metadataManager->getByName($name);

            return $meta->isPublic() && (
                !$requiredPermission
                || ($requiredPermission && $this->permissionManager->hasConfig($meta->getClass()))
            );
        }

        return false;
    }

    public function getMetadata(string $name, bool $requiredPermission = false): ViewMetadataInterface
    {
        if (!$this->hasMetadata($name, $requiredPermission)) {
            throw new ObjectMetadataNotFoundException($name);
        }

        $meta = $this->buildObjectMetadata($this->metadataManager->getByName($name));

        if (null === $meta) {
            throw new ObjectMetadataNotFoundException($name);
        }

        return $meta;
    }

    public function getChoices(): array
    {
        $choices = [];

        foreach ($this->metadataManager->allChoices() as $choice) {
            $choices[] = new ViewChoice(
                $choice->getName(),
                ChoiceUtil::getTrans($this->translator, $choice->getListIdentifiers(), $choice->getTranslationDomain()),
                ChoiceUtil::getTransPlaceholder($this->translator, $choice->getPlaceholder(), $choice->getTranslationDomain())
            );
        }

        return $choices;
    }

    public function hasChoice(string $name): bool
    {
        return $this->metadataManager->hasChoice($name);
    }

    public function getChoice(string $name): ViewChoiceInterface
    {
        if (!$this->metadataManager->hasChoice($name)
                || empty(($choice = $this->metadataManager->getChoice($name))->getListIdentifiers())) {
            throw new ChoiceNotFoundException($name);
        }

        return new ViewChoice(
            $choice->getName(),
            ChoiceUtil::getTrans($this->translator, $choice->getListIdentifiers(), $choice->getTranslationDomain()),
            ChoiceUtil::getTransPlaceholder($this->translator, $choice->getPlaceholder(), $choice->getTranslationDomain())
        );
    }

    public function getPermissions(RoleInterface $role): array
    {
        return $this->buildRolePermissions($role);
    }

    public function getObjectPermissions(RoleInterface $role, string $object, bool $requiredPermission = false): ObjectPermissionMetadataInterface
    {
        $meta = $this->getMetadata($object, $requiredPermission);
        $permissions = $this->buildRolePermissions($role, $meta->getClass());
        $fields = [];
        $associations = [];

        foreach ($meta->getFields() as $field) {
            $fieldMeta = new FieldPermissionMetadata(
                $field,
                $this->buildRolePermissions($role, new FieldVote($meta->getClass(), $field->getField()))
            );

            if (!empty($fieldMeta->getPermissions()) || (!$requiredPermission && !$fieldMeta->hasEditablePermissions())) {
                $fields[$field->getName()] = $fieldMeta;
            }
        }

        foreach ($meta->getAssociations() as $association) {
            $associationMeta = new AssociationPermissionMetadata(
                $association,
                $this->buildRolePermissions($role, new FieldVote($meta->getClass(), $association->getAssociation()))
            );

            if (!empty($associationMeta->getPermissions()) || (!$requiredPermission && !$associationMeta->hasEditablePermissions())) {
                $associations[$association->getName()] = $associationMeta;
            }
        }

        return new ObjectPermissionMetadata($meta, $permissions, $fields, $associations);
    }

    public function buildPermission(PermissionInterface $permission, bool $granted = true, bool $locked = false): PermissionMetadataInterface
    {
        $domainTrans = method_exists($permission, 'getTranslationDomain') ? $permission->getTranslationDomain() : null;
        $label = method_exists($permission, 'getLabel') ? $permission->getLabel() : null;
        $description = method_exists($permission, 'getDetailLabel') ? $permission->getDetailLabel() : null;

        return new PermissionMetadata(
            $permission,
            $granted,
            $locked,
            MetadataUtil::getTrans($this->translator, $label, $domainTrans, $permission->getOperation()),
            MetadataUtil::getTrans($this->translator, $description, $domainTrans)
        );
    }

    /**
     * @param mixed $permissions
     */
    public function buildPermissions($permissions, bool $granted = true, bool $locked = false): array
    {
        $data = [];

        foreach ($permissions as $permission) {
            $data[$permission->getOperation()] = $this->buildPermission($permission, $granted, $locked);
        }

        return $data;
    }

    /**
     * Build the permissions of role and subject.
     *
     * @param RoleInterface                                         $role    The role
     * @param null|FieldVote|object|string|SubjectIdentityInterface $subject The object or class name or field vote
     */
    private function buildRolePermissions(RoleInterface $role, $subject = null): array
    {
        $permissionCheckings = $this->permissionManager->getRolePermissions($role, $subject);
        $metadatas = [];

        foreach ($permissionCheckings as $permCheck) {
            $perm = $permCheck->getPermission();
            $metadatas[$perm->getOperation()] = $this->buildPermission($perm, $permCheck->isGranted(), $permCheck->isLocked());
        }

        return $metadatas;
    }

    /**
     * Build the metadata of object.
     *
     * @param ObjectMetadataInterface $metadata The object metadata
     */
    private function buildObjectMetadata(ObjectMetadataInterface $metadata): ?ViewMetadata
    {
        $viewMeta = null;

        if ($metadata->isPublic() && $this->authChecker->isGranted(new PermVote('view'), $metadata->getClass())) {
            $config = $this->permissionManager->hasConfig($metadata->getClass())
                ? $this->permissionManager->getConfig($metadata->getClass())
                : null;
            $master = $config && $config->getMaster() ? (string) $config->getMaster() : null;
            $viewMeta = new ViewMetadata(
                $metadata->getClass(),
                $metadata->getName(),
                $metadata->getPluralName(),
                $metadata->getFieldIdentifier(),
                $metadata->getFieldLabel(),
                MetadataUtil::getTrans($this->translator, $metadata->getLabel(), $metadata->getTranslationDomain(), $metadata->getName()),
                MetadataUtil::getTrans($this->translator, $metadata->getPluralLabel(), $metadata->getTranslationDomain(), $metadata->getName()),
                MetadataUtil::getTrans($this->translator, $metadata->getDescription(), $metadata->getTranslationDomain()),
                $metadata->isMultiSortable(),
                $this->buildDefaultSortable($metadata),
                $metadata->getAvailableContexts(),
                null === $master && null !== $config && empty($config->getOperations()),
                !empty($metadata->getActions()) ? array_keys($metadata->getActions()) : null,
                $this->buildFieldMetadatas($metadata, null === $master),
                $this->buildAssociationMetadatas($metadata, null === $master, $master)
            );
        }

        return $viewMeta;
    }

    /**
     * Build the list of default sortable.
     *
     * @param ObjectMetadataInterface $metadata The object metadata
     */
    private function buildDefaultSortable(ObjectMetadataInterface $metadata): array
    {
        $defaultSortable = [];

        foreach ($metadata->getDefaultSortable() as $field => $direction) {
            $metaForField = $metadata;

            if (false !== strpos($field, '.')) {
                $links = explode('.', $field);
                $field = array_pop($links);
                $metaForField = $this->getMetadataForField($metadata, $links);
            }

            $fieldMeta = $metaForField && $metaForField->hasFieldByName($field)
                ? $metaForField->getFieldByName($field)
                : null;

            if ($fieldMeta && $fieldMeta->isSortable() && $this->isVisibleField($metaForField, $fieldMeta)) {
                $field = $metaForField && $metadata !== $metaForField
                    ? $metaForField->getName().'.'.$fieldMeta->getName()
                    : $fieldMeta->getName();
                $defaultSortable[$field] = $direction;
            }
        }

        return $defaultSortable;
    }

    /**
     * Build the metadatas of fields.
     *
     * @param ObjectMetadataInterface $metadata            The object metadata
     * @param bool                    $editablePermissions Check if the permission is configurable for the object
     *
     * @return null|ViewFieldMetadataInterface[]
     */
    private function buildFieldMetadatas(ObjectMetadataInterface $metadata, $editablePermissions = false): ?array
    {
        $fields = null;

        foreach ($metadata->getFields() as $fieldMeta) {
            $name = $fieldMeta->getName();

            if ($fieldMeta->isPublic() && $this->isVisibleField($metadata, $fieldMeta)) {
                $fields[$name] = new ViewFieldMetadata(
                    $fieldMeta->getField(),
                    $fieldMeta->getName(),
                    $fieldMeta->getType(),
                    MetadataUtil::getTrans($this->translator, $fieldMeta->getLabel(), $fieldMeta->getTranslationDomain(), $fieldMeta->getName()),
                    MetadataUtil::getTrans($this->translator, $fieldMeta->getDescription(), $fieldMeta->getTranslationDomain()),
                    $fieldMeta->isSortable(),
                    $fieldMeta->isFilterable(),
                    $fieldMeta->isSearchable(),
                    $fieldMeta->isTranslatable(),
                    $fieldMeta->isReadOnly(),
                    $fieldMeta->isRequired(),
                    $fieldMeta->getInput(),
                    $fieldMeta->getInputConfig(),
                    $editablePermissions && $this->hasEditablePermissions($metadata, $fieldMeta->getField())
                );
            }
        }

        return $fields;
    }

    /**
     * Build the metadatas of associations.
     *
     * @param ObjectMetadataInterface $metadata            The object metadata
     * @param bool                    $editablePermissions Check if the permission is configurable for the object
     * @param null|string             $master              The master association name
     *
     * @return null|ViewAssociationMetadataInterface[]
     */
    private function buildAssociationMetadatas(ObjectMetadataInterface $metadata, bool $editablePermissions = false, ?string $master = null): ?array
    {
        $associations = null;

        foreach ($metadata->getAssociations() as $associationMeta) {
            $name = $associationMeta->getName();

            if ($associationMeta->isPublic() && $this->isVisibleAssociation($metadata, $associationMeta)) {
                $associations[$name] = new ViewAssociationMetadata(
                    $associationMeta->getAssociation(),
                    $associationMeta->getName(),
                    $associationMeta->getType(),
                    $associationMeta->getTarget(),
                    MetadataUtil::getTrans($this->translator, $associationMeta->getLabel(), $associationMeta->getTranslationDomain(), $associationMeta->getName()),
                    MetadataUtil::getTrans($this->translator, $associationMeta->getDescription(), $associationMeta->getTranslationDomain()),
                    $associationMeta->isReadOnly(),
                    $associationMeta->isRequired(),
                    $associationMeta->getInput(),
                    $associationMeta->getInputConfig(),
                    $editablePermissions && $this->hasEditablePermissions($metadata, $associationMeta->getAssociation()),
                    $master === $associationMeta->getAssociation()
                );
            }
        }

        return $associations;
    }

    /**
     * Check if the property of class has editable permissions.
     *
     * @param ObjectMetadataInterface $metadata The object metadata
     * @param string                  $field    The property class name
     */
    private function hasEditablePermissions(ObjectMetadataInterface $metadata, string $field): bool
    {
        $editablePermissions = false;
        $class = $metadata->getClass();

        if ($this->permissionManager->hasConfig($class)) {
            $config = $this->permissionManager->getConfig($class);
            $editablePermissions = $config->hasField($field) && $config->getField($field)->isEditable();
        }

        return $editablePermissions;
    }

    /**
     * Sort metadatas by label.
     *
     * @param ViewMetadata $metadata1 The first metadata
     * @param ViewMetadata $metadata2 The second metadata
     */
    private function sortMetadatas(ViewMetadata $metadata1, ViewMetadata $metadata2): int
    {
        return strcmp($metadata1->getLabel(), $metadata2->getLabel());
    }

    /**
     * Get the object metadata of the target association.
     *
     * @param ObjectMetadataInterface $metadata     The object metadata
     * @param string[]                $associations The recursive association names
     */
    private function getMetadataForField(ObjectMetadataInterface $metadata, array $associations): ?ObjectMetadataInterface
    {
        $assoMetadata = $metadata;

        foreach ($associations as $association) {
            if ($metadata->hasAssociationByName($association)) {
                $assoMeta = $metadata->getAssociationByName($association);

                if ($this->isVisibleAssociation($metadata, $assoMeta)
                        && \in_array($assoMeta->getType(), ['one-to-one', 'many-to-one'], true)) {
                    $assoMetadata = $this->metadataManager->get($assoMeta->getTarget());
                } else {
                    $assoMetadata = null;

                    break;
                }
            }
        }

        return $assoMetadata;
    }

    /**
     * Check if the field is visible.
     *
     * @param ObjectMetadataInterface $metadata      The object metadata
     * @param FieldMetadataInterface  $fieldMetadata The field metadata
     */
    private function isVisibleField(ObjectMetadataInterface $metadata, FieldMetadataInterface $fieldMetadata): bool
    {
        return $fieldMetadata->isPublic()
            && $this->authChecker->isGranted(new PermVote('read'), new FieldVote($metadata->getClass(), $fieldMetadata->getField()));
    }

    /**
     * Check if the association is visible.
     *
     * @param ObjectMetadataInterface      $metadata            The object metadata
     * @param AssociationMetadataInterface $associationMetadata The association metadata
     */
    private function isVisibleAssociation(ObjectMetadataInterface $metadata, AssociationMetadataInterface $associationMetadata): bool
    {
        return $associationMetadata->isPublic()
            && $this->authChecker->isGranted(new PermVote('read'), new FieldVote($metadata->getClass(), $associationMetadata->getAssociation()));
    }
}
