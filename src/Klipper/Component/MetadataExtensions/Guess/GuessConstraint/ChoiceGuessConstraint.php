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

use Klipper\Component\Choice\ChoiceInterface;
use Klipper\Component\Choice\NameableChoiceInterface;
use Klipper\Component\Choice\PlaceholderableChoiceInterface;
use Klipper\Component\Metadata\AssociationMetadataBuilderInterface;
use Klipper\Component\Metadata\ChildMetadataBuilderInterface;
use Klipper\Component\Metadata\ChoiceBuilder;
use Klipper\Component\Metadata\FieldMetadataBuilderInterface;
use Klipper\Component\Metadata\ObjectMetadataBuilderInterface;
use Klipper\Component\Metadata\Util\ChoiceUtil;
use Klipper\Component\Metadata\Util\MetadataUtil;
use Klipper\Component\MetadataExtensions\Guess\GuessSymfonyConstraint;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Constraints\Choice;
use Symfony\Component\Validator\Exception\ConstraintDefinitionException;

/**
 * @author François Pluchino <francois.pluchino@klipper.dev>
 */
class ChoiceGuessConstraint extends AbstractGuessConstraint implements GuessConstraintAwareInterface
{
    private ?GuessSymfonyConstraint $constraintGuesser = null;

    public function setGuesser(GuessSymfonyConstraint $guesser): void
    {
        $this->constraintGuesser = $guesser;
    }

    public function supports(ChildMetadataBuilderInterface $builder, Constraint $constraint): bool
    {
        return $constraint instanceof Choice;
    }

    /**
     * @param Choice|Constraint $constraint
     */
    public function guess(ChildMetadataBuilderInterface $builder, Constraint $constraint): void
    {
        $objectBuilder = $builder->getParent();

        if ($constraint->callback) {
            if (!\is_callable($choices = [$objectBuilder->getClass(), $constraint->callback])
                && !\is_callable($choices = $constraint->callback)
            ) {
                throw new ConstraintDefinitionException('The Choice constraint expects a valid callback');
            }

            $choices = \is_string($choices) ? explode('::', $choices) : $choices;
            $placeholder = null;

            if (\is_array($choices) && 2 === \count($choices)
                    && interface_exists(ChoiceInterface::class)
                    && is_a($choices[0], ChoiceInterface::class, true)) {
                $choicesCallback = [$choices[0], 'listIdentifiers'];
                $valuesCallback = [$choices[0], 'getValues'];
                $translationDomainCallback = [$choices[0], 'getTranslationDomain'];

                if (is_a($choices[0], NameableChoiceInterface::class, true)) {
                    $nameCallback = [$choices[0], 'getName'];
                    $name = $nameCallback();
                } else {
                    $name = strtolower(preg_replace('/(?<!^)[A-Z]/', '_$0', basename($choices[0])));
                }

                if (is_a($choices[0], PlaceholderableChoiceInterface::class, true)) {
                    $placeholderCallback = [$choices[0], 'getPlaceholder'];
                    $placeholder = $placeholderCallback();
                }

                $choices = $choicesCallback();
                $values = $valuesCallback();
                $translationDomain = $translationDomainCallback();
            } else {
                $name = $this->getChoiceName($objectBuilder, $builder);
                $translationDomain = null;
                $choiceValues = $choices();
                $choices = [];
                $values = null;

                foreach ($choiceValues as $value) {
                    $choices[$value] = ChoiceUtil::humanize($value);
                }
            }
        } else {
            $name = $this->getChoiceName($objectBuilder, $builder);
            $translationDomain = null;
            $choices = [];
            $values = null;
            $placeholder = null;

            foreach ($constraint->choices ?? [] as $value) {
                $choices[$value] = ChoiceUtil::humanize($value);
            }
        }

        $this->addType($builder, '?array');
        $this->addInput($builder, 'choice', [
            'multiple' => $constraint->multiple,
            'choice_min' => $constraint->min,
            'choice_max' => $constraint->max,
        ]);

        if (!empty($choices)) {
            $choice = new ChoiceBuilder(
                $name,
                $translationDomain,
                $choices,
                $values,
                $placeholder
            );

            foreach ($objectBuilder->getResources() as $resource) {
                $choice->addResource($resource);
            }

            $this->constraintGuesser->getRegistry()->addChoice($choice);

            $this->addInput($builder, 'choice', [
                'choices' => '#/choices/'.$name,
            ]);
        }
    }

    /**
     * Get the choice name.
     *
     * @param ObjectMetadataBuilderInterface $objectBuilder The object builder
     * @param ChildMetadataBuilderInterface  $builder       The child builder
     */
    private function getChoiceName(ObjectMetadataBuilderInterface $objectBuilder, ChildMetadataBuilderInterface $builder): string
    {
        $objectName = $objectBuilder->getName() ?? MetadataUtil::getObjectName($objectBuilder->getClass());
        $fieldName = $builder->getName();

        if (null === $fieldName) {
            if ($builder instanceof FieldMetadataBuilderInterface) {
                $fieldName = $builder->getField();
            } elseif ($builder instanceof AssociationMetadataBuilderInterface) {
                $fieldName = $builder->getAssociation();
            }
        }

        return $objectName.'_'.$fieldName;
    }
}
