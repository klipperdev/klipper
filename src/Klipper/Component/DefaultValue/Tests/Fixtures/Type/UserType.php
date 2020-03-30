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
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function newInstance(ObjectBuilderInterface $builder, array $options)
    {
        $class = $this->getClass();

        return new $class($options['username'], $options['password']);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'username' => 'test',
            'password' => 'password',
        ]);
    }

    public function getClass()
    {
        return 'Klipper\Component\DefaultValue\Tests\Fixtures\Object\User';
    }
}
