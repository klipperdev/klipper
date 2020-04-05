<?php

/*
 * This file is part of the Klipper package.
 *
 * (c) François Pluchino <francois.pluchino@klipper.dev>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Klipper\Component\Phone\Metadata\GuessConstraint;

use Klipper\Component\Metadata\ChildMetadataBuilderInterface;
use Klipper\Component\MetadataExtensions\Guess\GuessConstraint\AbstractGuessConstraint;
use Klipper\Component\Phone\Validator\Constraints\Phone;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Constraints\Bic;

/**
 * @author François Pluchino <francois.pluchino@klipper.dev>
 */
class PhoneGuessConstraint extends AbstractGuessConstraint
{
    public function supports(ChildMetadataBuilderInterface $builder, Constraint $constraint): bool
    {
        return $constraint instanceof Phone;
    }

    /**
     * @param Bic|Constraint $constraint
     */
    public function guess(ChildMetadataBuilderInterface $builder, Constraint $constraint): void
    {
        $this->addType($builder, '?string');
        $this->addInput($builder, 'phone');
    }
}
