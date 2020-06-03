<?php

/*
 * This file is part of the Klipper package.
 *
 * (c) François Pluchino <francois.pluchino@klipper.dev>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Klipper\Component\MetadataExtensions\Guess;

use Klipper\Component\Metadata\FieldMetadataBuilderInterface;
use Klipper\Component\Metadata\Guess\GuessFieldConfigInterface;
use Klipper\Component\Metadata\Guess\GuessRegistryAwareInterface;
use Klipper\Component\Metadata\MetadataRegistryInterface;
use Klipper\Component\MetadataExtensions\Guess\GuessConstraint\GuessConstraintAwareInterface;
use Klipper\Component\MetadataExtensions\Guess\GuessConstraint\GuessConstraintInterface;
use Symfony\Component\Validator\Mapping\ClassMetadataInterface;
use Symfony\Component\Validator\Mapping\Factory\MetadataFactoryInterface;

/**
 * @author François Pluchino <francois.pluchino@klipper.dev>
 */
class GuessSymfonyConstraint implements GuessFieldConfigInterface, GuessRegistryAwareInterface
{
    private MetadataFactoryInterface $factory;

    private ?MetadataRegistryInterface $metadataRegistry = null;

    /**
     * @var GuessConstraintInterface[]
     */
    private array $guessConstraints;

    /**
     * @param MetadataFactoryInterface   $factory          The jms metadata factory
     * @param GuessConstraintInterface[] $guessConstraints The guess constraints
     */
    public function __construct(MetadataFactoryInterface $factory, array $guessConstraints = [])
    {
        $this->factory = $factory;
        $this->guessConstraints = [];

        foreach ($guessConstraints as $guessConstraint) {
            $this->guessConstraints[] = $guessConstraint;

            if ($guessConstraint instanceof GuessConstraintAwareInterface) {
                $guessConstraint->setGuesser($this);
            }
        }
    }

    /**
     * @return GuessConstraintInterface[]
     */
    public function getGuessConstraints(): array
    {
        return $this->guessConstraints;
    }

    public function setRegistry(MetadataRegistryInterface $registry): void
    {
        $this->metadataRegistry = $registry;
    }

    public function getRegistry(): MetadataRegistryInterface
    {
        return $this->metadataRegistry;
    }

    public function guessFieldConfig(FieldMetadataBuilderInterface $builder): void
    {
        $field = $builder->getField();
        $meta = $this->factory->getMetadataFor($builder->getParent()->getClass());

        if ($meta instanceof ClassMetadataInterface && $meta->hasPropertyMetadata($field)) {
            foreach ($meta->getPropertyMetadata($field) as $memberMetadata) {
                foreach ($memberMetadata->getConstraints() as $constraint) {
                    foreach ($this->guessConstraints as $guessConstraint) {
                        if ($guessConstraint->supports($builder, $constraint)) {
                            $guessConstraint->guess($builder, $constraint);
                        }
                    }
                }
            }
        }
    }
}
