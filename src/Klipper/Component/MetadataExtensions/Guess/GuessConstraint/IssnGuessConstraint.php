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
use Symfony\Component\Validator\Constraints\Issn;

/**
 * @author François Pluchino <francois.pluchino@klipper.dev>
 */
class IssnGuessConstraint extends AbstractGuessConstraint
{
    public function supports(ChildMetadataBuilderInterface $builder, Constraint $constraint): bool
    {
        return $constraint instanceof Issn;
    }

    /**
     * @param Constraint|Issn $constraint
     */
    public function guess(ChildMetadataBuilderInterface $builder, Constraint $constraint): void
    {
        $this->addType($builder, '?string');
        $this->addInput($builder, 'issn', [
            'issn_case_sensitive' => $constraint->caseSensitive,
            'issn_require_hyphen' => $constraint->requireHyphen,
        ]);
    }
}
