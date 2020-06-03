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
use Symfony\Component\Validator\Constraints\Collection;
use Symfony\Component\Validator\Constraints\Required;

/**
 * @author François Pluchino <francois.pluchino@klipper.dev>
 */
class CollectionGuessConstraint extends AbstractGuessConstraint implements GuessConstraintAwareInterface
{
    private ?GuessSymfonyConstraint $guesser = null;

    public function supports(ChildMetadataBuilderInterface $builder, Constraint $constraint): bool
    {
        return $constraint instanceof Collection;
    }

    public function setGuesser(GuessSymfonyConstraint $guesser): void
    {
        $this->guesser = $guesser;
    }

    /**
     * @param Collection|Constraint $constraint
     */
    public function guess(ChildMetadataBuilderInterface $builder, Constraint $constraint): void
    {
        $mockObjectBuilder = new ObjectMetadataBuilder($builder->getParent()->getClass());
        $configFields = [];

        foreach ($constraint->fields as $field => $fieldConstraint) {
            /** @var Constraint[] $entryConstraints */
            $entryConstraints = $fieldConstraint->constraints;
            $mockChildBuilder = new FieldMetadataBuilder($field);
            $mockObjectBuilder->addField($mockChildBuilder);
            $fieldConfig = [
                'required_key' => $fieldConstraint instanceof Required,
            ];

            foreach ($entryConstraints as $entryConstraint) {
                foreach ($this->guesser->getGuessConstraints() as $guessConstraint) {
                    if ($guessConstraint->supports($builder, $entryConstraint)) {
                        $guessConstraint->guess($mockChildBuilder, $entryConstraint);

                        break;
                    }
                }
            }

            if ($entryType = $mockChildBuilder->getType()) {
                $fieldConfig['type'] = $entryType;
            }

            if ($entryInput = $mockChildBuilder->getInput()) {
                $fieldConfig['input'] = $entryInput;
            }

            if (!empty($entryInputConfig = $mockChildBuilder->getInputConfig())) {
                $fieldConfig['input_config'] = $entryInputConfig;
            }

            if (null !== ($entryRequired = $mockChildBuilder->isRequired())) {
                $fieldConfig['required_value'] = $entryRequired;
            }

            $configFields[$field] = $fieldConfig;
        }

        $this->addType($builder, '?object');
        $this->addInput($builder, '?collection', [
            'allow_extra_fields' => $constraint->allowExtraFields,
            'allow_missing_fields' => $constraint->allowMissingFields,
            'fields' => $configFields,
        ]);
    }
}
