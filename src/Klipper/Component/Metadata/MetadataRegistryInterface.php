<?php

/*
 * This file is part of the Klipper package.
 *
 * (c) François Pluchino <francois.pluchino@klipper.dev>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Klipper\Component\Metadata;

use Klipper\Component\Metadata\Exception\InvalidArgumentException;
use Klipper\Component\Metadata\Guess\GuessConfigInterface;
use Klipper\Component\Metadata\Loader\ChoiceNameCollection;
use Klipper\Component\Metadata\Loader\MetadataLoaderInterface;
use Klipper\Component\Metadata\Loader\ObjectMetadataNameCollection;

/**
 * @author François Pluchino <francois.pluchino@klipper.dev>
 */
interface MetadataRegistryInterface
{
    /**
     * Add the metadata loader.
     *
     * @param MetadataLoaderInterface $loader The metadata loader
     *
     * @return static
     */
    public function addLoader(MetadataLoaderInterface $loader);

    /**
     * Add the guess config of metadata.
     *
     * @param GuessConfigInterface $guessConfig The guess config of metadata
     *
     * @return static
     */
    public function addGuessConfig(GuessConfigInterface $guessConfig);

    /**
     * Add the object metadata.
     *
     * @param ObjectMetadataBuilderInterface $metadataBuilder The object metadata builder
     *
     * @return static
     */
    public function addBuilder(ObjectMetadataBuilderInterface $metadataBuilder);

    /**
     * Get the object metadata builder.
     *
     * @param string $class The class name
     */
    public function getBuilder(string $class): ?ObjectMetadataBuilderInterface;

    /**
     * Guess the configuration of metadata builder.
     *
     * @param ObjectMetadataBuilderInterface $builder The object metadata builder
     */
    public function guessConfig(ObjectMetadataBuilderInterface $builder): ObjectMetadataBuilderInterface;

    /**
     * Get the map of metadata short names and class names.
     */
    public function getNames(): ObjectMetadataNameCollection;

    /**
     * Register the choice builder.
     *
     * @param ChoiceBuilderInterface $choiceBuilder The choice builder
     *
     * @return static
     *
     * @throws InvalidArgumentException If the choice class does not implement the ChoiceInterface
     */
    public function addChoice(ChoiceBuilderInterface $choiceBuilder);

    /**
     * Get the choice builder.
     *
     * @param string $name The choice name
     */
    public function getChoice(string $name): ?ChoiceBuilderInterface;

    /**
     * Get the choice names.
     */
    public function getChoiceNames(): ChoiceNameCollection;
}
