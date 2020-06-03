<?php

/*
 * This file is part of the Klipper package.
 *
 * (c) François Pluchino <francois.pluchino@klipper.dev>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Klipper\Component\MetadataExtensions\Form\Type;

use Klipper\Component\MetadataExtensions\Form\Event\RolePermissionSubscriber;
use Klipper\Component\MetadataExtensions\Permission\PermissionMetadataInterface;
use Klipper\Component\MetadataExtensions\Permission\PermissionMetadataManagerInterface;
use Klipper\Component\Security\Model\RoleInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Role Permission Form Type.
 *
 * @author François Pluchino <francois.pluchino@klipper.dev>
 */
class RolePermissionType extends AbstractType
{
    protected PermissionMetadataManagerInterface $pmManager;

    /**
     * @param PermissionMetadataManagerInterface $pmManager The permission metadata manager
     */
    public function __construct(PermissionMetadataManagerInterface $pmManager)
    {
        $this->pmManager = $pmManager;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        /** @var RoleInterface $role */
        $role = $builder->getData();
        $permissionMetadatas = $this->pmManager->getPermissions($role);

        $this->buildPermissions($builder, $permissionMetadatas);
        $builder->addEventSubscriber(new RolePermissionSubscriber($role, $permissionMetadatas));
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->addAllowedTypes('data', RoleInterface::class);
    }

    public function getBlockPrefix(): string
    {
        return 'role_permission';
    }

    /**
     * Build the permissions.
     *
     * @param FormBuilderInterface          $builder             The form builder
     * @param PermissionMetadataInterface[] $permissionMetadatas The permission metadatas
     */
    private function buildPermissions(FormBuilderInterface $builder, array $permissionMetadatas): void
    {
        foreach ($permissionMetadatas as $permissionChecking) {
            $builder->add($permissionChecking->getOperation(), CheckboxType::class, [
                'mapped' => false,
                'data' => $permissionChecking->isGranted(),
                'label' => $permissionChecking->getLabel(),
                'translation_domain' => false,
                'required' => false,
            ]);

            $builder->get($permissionChecking->getOperation())->setAttribute('description', $permissionChecking->getDescription());
        }
    }
}
