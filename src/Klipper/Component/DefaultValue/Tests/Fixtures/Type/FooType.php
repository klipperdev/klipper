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
    /**
     * {@inheritdoc}
     */
    public function newInstance(ObjectBuilderInterface $builder, array $options): void
    {
        // force the test to create instance with the default type
    }

    /**
     * {@inheritdoc}
     */
    public function getClass()
    {
        return 'Klipper\Component\DefaultValue\Tests\Fixtures\Object\Foo';
    }
}
