<?php

/*
 * This file is part of the Klipper package.
 *
 * (c) François Pluchino <francois.pluchino@klipper.dev>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Klipper\Component\DefaultValue\Tests\Fixtures\Type;

use Klipper\Component\DefaultValue\AbstractType;
use Klipper\Component\DefaultValue\ObjectBuilderInterface;

class FooType extends AbstractType
{
    public function newInstance(ObjectBuilderInterface $builder, array $options): ?object
    {
        // force the test to create instance with the default type

        return null;
    }

    public function getClass(): string
    {
        return 'Klipper\Component\DefaultValue\Tests\Fixtures\Object\Foo';
    }
}
