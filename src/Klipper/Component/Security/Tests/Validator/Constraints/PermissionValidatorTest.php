<?php

/*
 * This file is part of the Klipper package.
 *
 * (c) François Pluchino <francois.pluchino@klipper.dev>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Klipper\Component\Security\Tests\Validator\Constraints;

use Klipper\Component\Security\Permission\PermissionManagerInterface;
use Klipper\Component\Security\Tests\Fixtures\Model\MockObject;
use Klipper\Component\Security\Tests\Fixtures\Model\MockPermission;
use Klipper\Component\Security\Validator\Constraints\Permission;
use Klipper\Component\Security\Validator\Constraints\PermissionValidator;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Validator\Context\ExecutionContextInterface;
use Symfony\Component\Validator\Violation\ConstraintViolationBuilderInterface;

/**
 * @author François Pluchino <francois.pluchino@klipper.dev>
 *
 * @internal
 */
final class PermissionValidatorTest extends TestCase
{
    /**
     * @var PermissionManagerInterface|\PHPUnit\Framework\MockObject\MockObject
     */
    protected $permissionManager;

    /**
     * @var ExecutionContextInterface|\PHPUnit\Framework\MockObject\MockObject
     */
    protected $context;

    /**
     * @var PermissionValidator
     */
    protected $validator;

    protected function setUp(): void
    {
        $this->permissionManager = $this->getMockBuilder(PermissionManagerInterface::class)->getMock();
        $this->context = $this->getMockBuilder(ExecutionContextInterface::class)->getMock();
        $this->validator = new PermissionValidator($this->permissionManager);
    }

    public function testValidateWithEmptyClassAndField(): void
    {
        $constraint = new Permission();
        $perm = new MockPermission();

        $this->context->expects(static::never())
            ->method('buildViolation')
        ;

        $this->validator->initialize($this->context);
        $this->validator->validate($perm, $constraint);
    }

    public function testValidateWithEmptyField(): void
    {
        $constraint = new Permission();
        $perm = new MockPermission();
        $perm->setClass(MockObject::class);

        $this->permissionManager->expects(static::once())
            ->method('hasConfig')
            ->with(MockObject::class)
            ->willReturn(true)
        ;

        $this->context->expects(static::never())
            ->method('buildViolation')
        ;

        $this->validator->initialize($this->context);
        $this->validator->validate($perm, $constraint);
    }

    public function testValidateWithInvalidClassName(): void
    {
        $constraint = new Permission();
        $perm = new MockPermission();
        $perm->setClass('FooBar');

        $this->permissionManager->expects(static::never())
            ->method('hasConfig')
        ;

        $vb = $this->getMockBuilder(ConstraintViolationBuilderInterface::class)->getMock();

        $this->context->expects(static::once())
            ->method('buildViolation')
            ->with('permission.class.invalid')
            ->willReturn($vb)
        ;

        $vb->expects(static::once())
            ->method('atPath')
            ->with('class')
            ->willReturn($vb)
        ;

        $vb->expects(static::at(1))
            ->method('setParameter')
            ->with('%class_property%', 'class')
            ->willReturn($vb)
        ;

        $vb->expects(static::at(2))
            ->method('setParameter')
            ->with('%class%', 'FooBar')
            ->willReturn($vb)
        ;

        $vb->expects(static::once())
            ->method('addViolation')
        ;

        $this->validator->initialize($this->context);
        $this->validator->validate($perm, $constraint);
    }

    public function testValidateWithNonManagedClass(): void
    {
        $constraint = new Permission();
        $perm = new MockPermission();
        $perm->setClass(MockObject::class);

        $this->permissionManager->expects(static::once())
            ->method('hasConfig')
            ->with(MockObject::class)
            ->willReturn(false)
        ;

        $vb = $this->getMockBuilder(ConstraintViolationBuilderInterface::class)->getMock();

        $this->context->expects(static::once())
            ->method('buildViolation')
            ->with('permission.class.not_managed')
            ->willReturn($vb)
        ;

        $vb->expects(static::once())
            ->method('atPath')
            ->with('class')
            ->willReturn($vb)
        ;

        $vb->expects(static::at(1))
            ->method('setParameter')
            ->with('%class_property%', 'class')
            ->willReturn($vb)
        ;

        $vb->expects(static::at(2))
            ->method('setParameter')
            ->with('%class%', MockObject::class)
            ->willReturn($vb)
        ;

        $vb->expects(static::once())
            ->method('addViolation')
        ;

        $this->validator->initialize($this->context);
        $this->validator->validate($perm, $constraint);
    }

    public function testValidateFieldWithEmptyClass(): void
    {
        $constraint = new Permission();
        $perm = new MockPermission();
        $perm->setField('name');

        $this->permissionManager->expects(static::never())
            ->method('hasConfig')
        ;

        $vb = $this->getMockBuilder(ConstraintViolationBuilderInterface::class)->getMock();

        $this->context->expects(static::once())
            ->method('buildViolation')
            ->with('permission.class.required')
            ->willReturn($vb)
        ;

        $vb->expects(static::at(0))
            ->method('atPath')
            ->with('class')
            ->willReturn($vb)
        ;

        $vb->expects(static::at(1))
            ->method('setParameter')
            ->with('%field_property%', 'field')
            ->willReturn($vb)
        ;

        $vb->expects(static::at(2))
            ->method('setParameter')
            ->with('%field%', 'name')
            ->willReturn($vb)
        ;

        $vb->expects(static::once())
            ->method('addViolation')
        ;

        $this->validator->initialize($this->context);
        $this->validator->validate($perm, $constraint);
    }

    public function testValidateFieldWithInvalidField(): void
    {
        $constraint = new Permission();
        $perm = new MockPermission();
        $perm->setClass(MockObject::class);
        $perm->setField('name2');

        $this->permissionManager->expects(static::once())
            ->method('hasConfig')
            ->with(MockObject::class)
            ->willReturn(true)
        ;

        $vb = $this->getMockBuilder(ConstraintViolationBuilderInterface::class)->getMock();

        $this->context->expects(static::once())
            ->method('buildViolation')
            ->with('permission.field.invalid')
            ->willReturn($vb)
        ;

        $vb->expects(static::at(0))
            ->method('atPath')
            ->with('field')
            ->willReturn($vb)
        ;

        $vb->expects(static::at(1))
            ->method('setParameter')
            ->with('%class_property%', 'class')
            ->willReturn($vb)
        ;

        $vb->expects(static::at(2))
            ->method('setParameter')
            ->with('%field_property%', 'field')
            ->willReturn($vb)
        ;

        $vb->expects(static::at(3))
            ->method('setParameter')
            ->with('%class%', MockObject::class)
            ->willReturn($vb)
        ;

        $vb->expects(static::at(4))
            ->method('setParameter')
            ->with('%field%', 'name2')
            ->willReturn($vb)
        ;

        $vb->expects(static::once())
            ->method('addViolation')
        ;

        $this->validator->initialize($this->context);
        $this->validator->validate($perm, $constraint);
    }

    public function testValidate(): void
    {
        $constraint = new Permission();
        $perm = new MockPermission();
        $perm->setClass(MockObject::class);
        $perm->setField('name');

        $this->permissionManager->expects(static::once())
            ->method('hasConfig')
            ->with(MockObject::class)
            ->willReturn(true)
        ;

        $this->context->expects(static::never())
            ->method('buildViolation')
        ;

        $this->validator->initialize($this->context);
        $this->validator->validate($perm, $constraint);
    }
}