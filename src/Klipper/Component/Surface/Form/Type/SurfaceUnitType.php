<?php

/*
 * This file is part of the Klipper package.
 *
 * (c) François Pluchino <francois.pluchino@klipper.dev>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Klipper\Component\Surface\Form\Type;

use Klipper\Component\Form\Util\FormChoiceUtil;
use Klipper\Component\Surface\Choice\SurfaceUnit;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Surface Unit Form Type.
 *
 * @author François Pluchino <francois.pluchino@klipper.dev>
 */
class SurfaceUnitType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'choices' => FormChoiceUtil::simpleList(SurfaceUnit::listIdentifiers()),
            'choice_translation_domain' => SurfaceUnit::getTranslationDomain(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function getParent(): string
    {
        return ChoiceType::class;
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix(): string
    {
        return 'surface_unit';
    }
}
