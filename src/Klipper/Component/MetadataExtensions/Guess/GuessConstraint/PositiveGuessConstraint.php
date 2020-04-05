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
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Constraints\GreaterThan;
use Symfony\Component\Validator\Constraints\Positive;

/**
 * @author François Pluchino <francois.pluchino@klipper.dev>
 */
class PositiveGuessConstraint extends AbstractGuessConstraint
{
    public function supports(ChildMetadataBuilderInterface $builder, Constraint $constraint): bool
    {
        return $constraint instanceof Positive;
    }

    /**
     * @param Constraint|GreaterThan $constraint
     */
    public function guess(ChildMetadataBuilderInterface $builder, Constraint $constraint): void
    {
        $this->addType($builder, '?number');
        $this->addInput($builder, '?number', [
            'greater_than_value' => $constraint->value,
        ]);
    }
}