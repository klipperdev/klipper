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
use Symfony\Component\Validator\Constraints\Length;

/**
 * @author François Pluchino <francois.pluchino@klipper.dev>
 */
class LengthGuessConstraint extends AbstractGuessConstraint
{
    public function supports(ChildMetadataBuilderInterface $builder, Constraint $constraint): bool
    {
        return $constraint instanceof Length;
    }

    /**
     * @param Constraint|Length $constraint
     */
    public function guess(ChildMetadataBuilderInterface $builder, Constraint $constraint): void
    {
        $this->addType($builder, '?string');
        $this->addInput($builder, '?text', [
            'length_min' => null !== $constraint->min ? (int) $constraint->min : null,
            'length_max' => null !== $constraint->max ? (int) $constraint->max : null,
        ]);

        if (($constraint->max ?? 0) > 255 && 'text' === $builder->getInput()) {
            $builder->setInput('textarea');
        }
    }
}
