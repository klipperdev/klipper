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
use Symfony\Component\Validator\Constraints\Count;

/**
 * @author François Pluchino <francois.pluchino@klipper.dev>
 */
class CountGuessConstraint extends AbstractGuessConstraint
{
    public function supports(ChildMetadataBuilderInterface $builder, Constraint $constraint): bool
    {
        return $constraint instanceof Count;
    }

    /**
     * @param Constraint|Count $constraint
     */
    public function guess(ChildMetadataBuilderInterface $builder, Constraint $constraint): void
    {
        $this->addType($builder, '?array');
        $this->addInput($builder, '?collection', [
            'count_min' => null !== $constraint->min ? (int) $constraint->min : null,
            'count_max' => null !== $constraint->max ? (int) $constraint->max : null,
        ]);
    }
}
