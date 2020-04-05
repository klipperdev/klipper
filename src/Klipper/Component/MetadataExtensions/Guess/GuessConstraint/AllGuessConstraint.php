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
use Klipper\Component\Metadata\FieldMetadataBuilder;
use Klipper\Component\Metadata\ObjectMetadataBuilder;
use Klipper\Component\MetadataExtensions\Guess\GuessSymfonyConstraint;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Constraints\All;

/**
 * @author François Pluchino <francois.pluchino@klipper.dev>
 */
class AllGuessConstraint extends AbstractGuessConstraint implements GuessConstraintAwareInterface
{
    /**
     * @var GuessSymfonyConstraint
     */
    private $guesser;

    public function supports(ChildMetadataBuilderInterface $builder, Constraint $constraint): bool
    {
        return $constraint instanceof All;
    }

    public function setGuesser(GuessSymfonyConstraint $guesser): void
    {
        $this->guesser = $guesser;
    }

    /**
     * @param All|Constraint $constraint
     */
    public function guess(ChildMetadataBuilderInterface $builder, Constraint $constraint): void
    {
        /** @var Constraint[] $constraints */
        $constraints = $constraint->constraints;
        $mockObjectBuilder = new ObjectMetadataBuilder($builder->getParent()->getClass());
        $mockChildBuilder = new FieldMetadataBuilder('entry');
        $mockObjectBuilder->addField($mockChildBuilder);
        $config = [];

        foreach ($constraints as $entryConstraint) {
            foreach ($this->guesser->getGuessConstraints() as $guessConstraint) {
                if ($guessConstraint->supports($builder, $entryConstraint)) {
                    $guessConstraint->guess($mockChildBuilder, $entryConstraint);

                    break;
                }
            }
        }

        if ($entryInput = $mockChildBuilder->getInput()) {
            $config['entry_input'] = $entryInput;
        }

        if (!empty($entryInputConfig = $mockChildBuilder->getInputConfig())) {
            $config['entry_config'] = $entryInputConfig;
        }

        if (null !== ($entryRequired = $mockChildBuilder->isRequired())) {
            $config['entry_required'] = $entryRequired;
        }

        $this->addInput($builder, '?collection', $config);
    }
}
