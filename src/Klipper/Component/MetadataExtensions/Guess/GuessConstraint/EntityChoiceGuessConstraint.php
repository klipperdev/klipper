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

use Klipper\Component\DoctrineExtensionsExtra\Validator\Constraints\EntityChoice;
use Klipper\Component\Metadata\ChildMetadataBuilderInterface;
use Klipper\Component\MetadataExtensions\Guess\GuessChoiceUtil;
use Klipper\Component\MetadataExtensions\Guess\GuessSymfonyConstraint;
use Symfony\Component\Validator\Constraint;

/**
 * @author François Pluchino <francois.pluchino@klipper.dev>
 */
class EntityChoiceGuessConstraint extends AbstractGuessConstraint implements GuessConstraintAwareInterface
{
    private ?GuessSymfonyConstraint $constraintGuesser = null;

    public function setGuesser(GuessSymfonyConstraint $guesser): void
    {
        $this->constraintGuesser = $guesser;
    }

    public function supports(ChildMetadataBuilderInterface $builder, Constraint $constraint): bool
    {
        return $constraint instanceof EntityChoice;
    }

    /**
     * @param Constraint|EntityChoice $constraint
     */
    public function guess(ChildMetadataBuilderInterface $builder, Constraint $constraint): void
    {
        GuessChoiceUtil::guessConfig($this->constraintGuesser->getRegistry(), $builder, $constraint->entityClass, $constraint->multiple);
    }
}
