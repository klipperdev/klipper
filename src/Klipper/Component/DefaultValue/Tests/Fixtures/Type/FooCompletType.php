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
use Klipper\Component\DefaultValue\Tests\Fixtures\Object\Foo;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FooCompletType extends AbstractType
{
    public function buildObject(ObjectBuilderInterface $builder, array $options): void
    {
        /** @var Foo $data */
        $data = $builder->getData();

        if (null === $data->getBar()) {
            $data->setBar($options['bar']);
        }
    }

    public function finishObject(ObjectBuilderInterface $builder, array $options): void
    {
        /** @var Foo $data */
        $data = $builder->getData();

        if ('the answer to life, the universe, and everything' === $data->getBar()) {
            $data->setBar('42');
        }
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'bar' => null,
        ]);

        $resolver->addAllowedTypes('bar', 'string');
    }

    public function getClass(): string
    {
        return 'Klipper\Component\DefaultValue\Tests\Fixtures\Object\Foo';
    }
}
