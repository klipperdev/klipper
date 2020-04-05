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

/**
 * @author François Pluchino <francois.pluchino@klipper.dev>
 */
interface GuessConstraintInterface
{
    public function supports(ChildMetadataBuilderInterface $builder, Constraint $constraint): bool;

    public function guess(ChildMetadataBuilderInterface $builder, Constraint $constraint): void;
}
