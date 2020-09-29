<?php

/*
 * This file is part of the Klipper package.
 *
 * (c) François Pluchino <francois.pluchino@klipper.dev>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Klipper\Component\MetadataExtensions\Guess\GuessConstraint;

use Klipper\Component\Metadata\ChildMetadataBuilderInterface;
use Symfony\Component\Intl\Languages;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Constraints\Language;

/**
 * @author François Pluchino <francois.pluchino@klipper.dev>
 */
class LanguageGuessConstraint extends AbstractGuessConstraint
{
    public function supports(ChildMetadataBuilderInterface $builder, Constraint $constraint): bool
    {
        return $constraint instanceof Language;
    }

    /**
     * @param Constraint|Language $constraint
     */
    public function guess(ChildMetadataBuilderInterface $builder, Constraint $constraint): void
    {
        $choices = Languages::getNames();

        $this->addType($builder, '?string');
        $this->addInput($builder, 'choice', [
            'choices' => array_keys($choices),
            'multiple' => false,
            'null_form_type' => true,
        ]);
    }
}
