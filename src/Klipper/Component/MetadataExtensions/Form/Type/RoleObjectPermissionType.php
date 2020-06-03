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

use Klipper\Component\MetadataExtensions\Form\Event\RoleObjectPermissionSubscriber;
use Klipper\Component\MetadataExtensions\Permission\AssociationPermissionMetadataInterface;
use Klipper\Component\MetadataExtensions\Permission\FieldPermissionMetadataInterface;
use Klipper\Component\MetadataExtensions\Permission\PermissionMetadataInterface;
use Klipper\Component\MetadataExtensions\Permission\PermissionMetadataManagerInterface;
use Klipper\Component\Security\Model\RoleInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Role Object Permission Form Type.
 *
 * @author François Pluchino <francois.pluchino@klipper.dev>
 */
class RoleObjectPermissionType extends AbstractType
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
        $objectMetadata = $this->pmManager->getObjectPermissions($role, $options['object']);

        $this->buildObject($builder, $objectMetadata->getPermissions());
        $this->buildFields($builder, $objectMetadata->getFields());
        $this->buildAssociations($builder, $objectMetadata->getAssociations());

        $builder->addEventSubscriber(new RoleObjectPermissionSubscriber($role, $objectMetadata));
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'object' => null,
        ]);

        $resolver->addAllowedTypes('data', RoleInterface::class);
        $resolver->addAllowedTypes('object', 'string');
    }

    public function getBlockPrefix(): string
    {
        return 'role_object_permission';
    }

    /**
     * Build the form fields of permissions.
     *
     * @param FormBuilderInterface          $builder             The form builder
     * @param PermissionMetadataInterface[] $permissionMetadatas The permission metadatas
     */
    private function buildPermissions(FormBuilderInterface $builder, array $permissionMetadatas): void
    {
        foreach ($permissionMetadatas as $permissionMetadata) {
            $builder->add($permissionMetadata->getOperation(), CheckboxType::class, [
                'disabled' => $permissionMetadata->isLocked(),
                'mapped' => false,
                'data' => $permissionMetadata->isGranted(),
                'label' => $permissionMetadata->getLabel(),
                'translation_domain' => false,
                'required' => false,
            ]);

            $builder->get($permissionMetadata->getOperation())->setAttribute('description', $permissionMetadata->getDescription());
        }
    }

    /**
     * Build the form fields of object permissions.
     *
     * @param FormBuilderInterface          $builder             The form builder
     * @param PermissionMetadataInterface[] $permissionMetadatas The permission metadatas
     */
    private function buildObject(FormBuilderInterface $builder, array $permissionMetadatas): void
    {
        if (empty($permissionMetadatas)) {
            return;
        }

        $permissionBuilder = $builder->add('permissions', WrapperPermissionType::class)->get('permissions');
        $this->buildPermissions($permissionBuilder, $permissionMetadatas);
    }

    /**
     * Build the form fields of field permissions.
     *
     * @param FormBuilderInterface               $builder        The form builder
     * @param FieldPermissionMetadataInterface[] $fieldMetadatas The field permission metadatas
     */
    private function buildFields(FormBuilderInterface $builder, array $fieldMetadatas): void
    {
        if (empty($fieldMetadatas)) {
            return;
        }

        $fieldsBuilder = $builder->add('fields', WrapperPermissionType::class)->get('fields');

        foreach ($fieldMetadatas as $fieldMetadata) {
            $name = $fieldMetadata->getName();
            $fieldBuilder = $fieldsBuilder->add($name, WrapperPermissionType::class, [
                'label' => $fieldMetadata->getLabel(),
                'required' => false,
            ])->get($name);

            $this->buildPermissions($fieldBuilder, $fieldMetadata->getPermissions());
        }
    }

    /**
     * Build the form association of association permissions.
     *
     * @param FormBuilderInterface                     $builder              The form builder
     * @param AssociationPermissionMetadataInterface[] $associationMetadatas The association permission metadatas
     */
    private function buildAssociations(FormBuilderInterface $builder, array $associationMetadatas): void
    {
        if (empty($associationMetadatas)) {
            return;
        }

        $associationsBuilder = $builder->add('associations', WrapperPermissionType::class)->get('associations');

        foreach ($associationMetadatas as $associationMetadata) {
            $name = $associationMetadata->getName();
            $fieldBuilder = $associationsBuilder->add($name, WrapperPermissionType::class, [
                'label' => $associationMetadata->getLabel(),
                'required' => false,
            ])->get($name);

            $this->buildPermissions($fieldBuilder, $associationMetadata->getPermissions());
        }
    }
}
