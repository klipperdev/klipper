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

use Klipper\Component\MetadataExtensions\Permission\PermissionMetadataInterface;
use Klipper\Component\Security\Model\RoleInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;

/**
 * Role Permission Event Subscriber.
 *
 * @author François Pluchino <francois.pluchino@klipper.dev>
 */
class RolePermissionSubscriber implements EventSubscriberInterface
{
    /**
     * @var RoleInterface
     */
    protected $role;

    /**
     * @var PermissionMetadataInterface[]
     */
    protected $permissionMetadatas;

    /**
     * Constructor.
     *
     * @param RoleInterface                 $role                The role
     * @param PermissionMetadataInterface[] $permissionMetadatas The permission metadatas
     */
    public function __construct(RoleInterface $role, array $permissionMetadatas)
    {
        $this->role = $role;
        $this->permissionMetadatas = $permissionMetadatas;
    }

    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents(): array
    {
        return [
            FormEvents::SUBMIT => 'onSubmit',
        ];
    }

    /**
     * Action on submit.
     *
     * @param FormEvent $event The event
     */
    public function onSubmit(FormEvent $event): void
    {
        $form = $event->getForm();
        $rolePermissions = [];

        foreach ($this->role->getPermissions() as $perm) {
            if (null === $perm->getClass()) {
                $rolePermissions[$perm->getOperation()] = $perm;
            }
        }

        foreach ($form->all() as $child) {
            if (true === $child->getData()) {
                if (!isset($rolePermissions[$child->getName()])) {
                    $meta = $this->permissionMetadatas[$child->getName()];
                    $this->role->addPermission($meta->getPermission());
                }
            } else {
                if (isset($rolePermissions[$child->getName()])) {
                    $this->role->removePermission($rolePermissions[$child->getName()]);
                }
            }
        }
    }
}
