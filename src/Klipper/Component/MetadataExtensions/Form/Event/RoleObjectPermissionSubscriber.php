<?php

/*
 * This file is part of the Klipper package.
 *
 * (c) François Pluchino <francois.pluchino@klipper.dev>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Klipper\Component\MetadataExtensions\Form\Event;

use Klipper\Component\MetadataExtensions\Permission\AssociationPermissionMetadataInterface;
use Klipper\Component\MetadataExtensions\Permission\FieldPermissionMetadataInterface;
use Klipper\Component\MetadataExtensions\Permission\ObjectPermissionMetadataInterface;
use Klipper\Component\MetadataExtensions\Permission\PermissionMetadataInterface;
use Klipper\Component\Security\Model\PermissionInterface;
use Klipper\Component\Security\Model\RoleInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;

/**
 * Role Object Permission Event Subscriber.
 *
 * @author François Pluchino <francois.pluchino@klipper.dev>
 */
class RoleObjectPermissionSubscriber implements EventSubscriberInterface
{
    protected RoleInterface $role;

    protected ObjectPermissionMetadataInterface $metadata;

    /**
     * @param RoleInterface                     $role     The role
     * @param ObjectPermissionMetadataInterface $metadata The object permissions metadata
     */
    public function __construct(RoleInterface $role, ObjectPermissionMetadataInterface $metadata)
    {
        $this->role = $role;
        $this->metadata = $metadata;
    }

    public static function getSubscribedEvents(): array
    {
        return [
            FormEvents::PRE_SUBMIT => 'onPreSubmit',
            FormEvents::SUBMIT => 'onSubmit',
        ];
    }

    /**
     * Action on submit.
     *
     * @param FormEvent $event The event
     */
    public function onPreSubmit(FormEvent $event): void
    {
        $data = $event->getData();

        $objects = $data['permissions'] ?? [];
        $objectPermissions = $this->metadata->getPermissions();
        $fields = $data['fields'] ?? [];
        $associations = $data['associations'] ?? [];

        if (isset($objectPermissions['view']) && ($this->isValidValue('create', $objects)
                || $this->isValidValue('update', $objects)
                || $this->isValidValue('delete', $objects)
                || $this->isValidValue('undelete', $objects))) {
            $data['permissions']['view'] = '1';
        }

        foreach ($fields as $field => $value) {
            $permissions = $this->metadata->hasField($field)
                ? $this->metadata->getField($field)->getPermissions()
                : [];

            if (isset($permissions['edit']) && $permissions['read'] && $this->isValidValue('edit', $value)) {
                $data['fields'][$field]['read'] = '1';
            }
        }

        foreach ($associations as $association => $value) {
            $permissions = $this->metadata->getAssociation($association)->getPermissions();

            if (isset($permissions['edit']) && $permissions['read'] && $this->isValidValue('edit', $value)) {
                $data['fields'][$association]['read'] = '1';
            }
        }

        $event->setData($data);
    }

    /**
     * Action on submit.
     *
     * @param FormEvent $event The event
     */
    public function onSubmit(FormEvent $event): void
    {
        $form = $event->getForm();
        $mapRolePermissions = [];

        foreach ($this->role->getPermissions() as $permission) {
            if (null !== $permission->getClass()) {
                $field = $permission->getField() ?? '_object';
                $mapRolePermissions[$field][$permission->getOperation()] = $permission;
            }
        }

        if ($form->has('permissions')) {
            $this->injectData($form->get('permissions'), $mapRolePermissions, $this->metadata->getPermissions());
        }

        if ($form->has('fields')) {
            $this->injectCollectionData($form->get('fields'), $mapRolePermissions, $this->metadata->getFields());
        }

        if ($form->has('associations')) {
            $this->injectCollectionData($form->get('associations'), $mapRolePermissions, $this->metadata->getAssociations());
        }
    }

    private function isValidValue(string $name, array $value): bool
    {
        return isset($value[$name]) && true === (bool) $value[$name];
    }

    /**
     * Inject the collection data.
     *
     * @param FormInterface                                                               $form                The form
     * @param PermissionInterface[]                                                       $mapRolePermissions  The map of role permissions
     * @param AssociationPermissionMetadataInterface[]|FieldPermissionMetadataInterface[] $collectionMetadatas The collection metadatas
     */
    private function injectCollectionData(FormInterface $form, array $mapRolePermissions, array $collectionMetadatas): void
    {
        foreach ($form->all() as $child) {
            $name = $child->getName();
            $collectionMeta = $collectionMetadatas[$name];
            $fieldName = $collectionMeta instanceof FieldPermissionMetadataInterface
                ? $collectionMeta->getField()
                : $collectionMeta->getAssociation();
            $this->injectData($child, $mapRolePermissions, $collectionMeta->getPermissions(), $fieldName);
        }
    }

    /**
     * Inject the form data in permission metadatas.
     *
     * @param FormInterface                 $form                The form
     * @param PermissionInterface[]         $mapRolePermissions  The map of role permissions
     * @param PermissionMetadataInterface[] $permissionMetadatas The permission metadatas
     * @param string                        $name                The field of association name
     */
    private function injectData(FormInterface $form, array $mapRolePermissions, array $permissionMetadatas, string $name = '_object'): void
    {
        foreach ($form->all() as $child) {
            if (true === $child->getData()) {
                if (!isset($mapRolePermissions[$name][$child->getName()])) {
                    $meta = $permissionMetadatas[$child->getName()];

                    if (!$meta->isLocked()) {
                        $this->role->addPermission($meta->getPermission());
                    }
                }
            } else {
                if (isset($mapRolePermissions[$name][$child->getName()])) {
                    $this->role->removePermission($mapRolePermissions[$name][$child->getName()]);
                }
            }
        }
    }
}
