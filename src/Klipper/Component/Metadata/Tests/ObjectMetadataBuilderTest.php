<?php

/*
 * This file is part of the Klipper package.
 *
 * (c) François Pluchino <francois.pluchino@klipper.dev>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Klipper\Component\Metadata\Tests;

use Klipper\Component\Metadata\AssociationMetadataBuilder;
use Klipper\Component\Metadata\FieldMetadataBuilder;
use Klipper\Component\Metadata\ObjectMetadataBuilder;
use PHPUnit\Framework\TestCase;

/**
 * @author François Pluchino <francois.pluchino@klipper.dev>
 *
 * @group klipper
 * @group klipper-metadata
 *
 * @internal
 */
final class ObjectMetadataBuilderTest extends TestCase
{
    public function testMergeObjectBuilder(): void
    {
        // objects
        $builder = new ObjectMetadataBuilder(\stdClass::class);
        $newBuilder = new ObjectMetadataBuilder(\stdClass::class);
        $newBuilder->setName('name');
        $newBuilder->setType('type');
        $newBuilder->setAvailableContexts(['context']);
        $newBuilder->setPublic(true);

        // fields
        $fieldBuilder = new FieldMetadataBuilder('field1');
        $fieldBuilder->setSearchable(true);
        $builder->addField($fieldBuilder);

        $newFieldBuilder = new FieldMetadataBuilder('field1');
        $newFieldBuilder->setSortable(true);
        $newBuilder->addField($newFieldBuilder);

        $newFieldBuilder2 = new FieldMetadataBuilder('field2');
        $newFieldBuilder2->setPublic(true);
        $newBuilder->addField($newFieldBuilder2);

        // associations
        $assoBuilder = new AssociationMetadataBuilder('asso1');
        $assoBuilder->setName('test');
        $builder->addAssociation($assoBuilder);

        $newAssoBuilder = new AssociationMetadataBuilder('asso1');
        $newAssoBuilder->setTarget(\stdClass::class);
        $newBuilder->addAssociation($newAssoBuilder);

        $newAssoBuilder2 = new AssociationMetadataBuilder('asso2');
        $newAssoBuilder2->setTarget(\stdClass::class);
        $newBuilder->addAssociation($newAssoBuilder2);

        // tests
        static::assertNull($builder->getName());
        static::assertSame('object', $builder->getType());
        static::assertNull($builder->getAvailableContexts());
        static::assertNull($builder->isPublic());
        static::assertFalse($builder->isSortable());
        static::assertNull($builder->isMultiSortable());

        $builder->merge($newBuilder);

        static::assertSame($newBuilder->getName(), $builder->getName());
        static::assertSame('object', $builder->getType());
        static::assertSame($newBuilder->getAvailableContexts(), $builder->getAvailableContexts());
        static::assertSame($newBuilder->isPublic(), $builder->isPublic());
        static::assertTrue($builder->isSortable());
        static::assertNull($builder->isMultiSortable());

        static::assertTrue($builder->hasField('field1'));
        static::assertTrue($builder->hasField('field2'));

        static::assertTrue($builder->getField('field1')->isSearchable());
        static::assertTrue($builder->getField('field1')->isSortable());
        static::assertTrue($builder->getField('field2')->isPublic());

        static::assertTrue($builder->hasAssociation('asso1'));
        static::assertTrue($builder->hasAssociation('asso2'));

        static::assertSame('test', $assoBuilder->getName());

        static::assertSame('test', $builder->getAssociation('asso1')->getName());
        static::assertSame(\stdClass::class, $builder->getAssociation('asso1')->getTarget());
        static::assertSame(\stdClass::class, $builder->getAssociation('asso2')->getTarget());
    }
}
