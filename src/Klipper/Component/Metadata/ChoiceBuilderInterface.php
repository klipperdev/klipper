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

use Symfony\Component\Config\Resource\ResourceInterface;

/**
 * @author François Pluchino <francois.pluchino@klipper.dev>
 */
interface ChoiceBuilderInterface
{
    /**
     * Get the unique name of the choice.
     */
    public function getName(): string;

    /**
     * Get the list of choice.
     */
    public function getListIdentifiers(): ?array;

    /**
     * Set the list identifiers.
     *
     * @param array $identifiers the map of values and labels or the map wrapped by the group labels
     *
     * @return static
     */
    public function setListIdentifiers(array $identifiers);

    /**
     * Get all values.
     *
     * @return null|string[]
     */
    public function getValues(): ?array;

    /**
     * Set the values.
     *
     * @param null|array $values The values
     *
     * @return static
     */
    public function setValues(?array $values);

    /**
     * Get the translation domain name.
     */
    public function getTranslationDomain(): ?string;

    /**
     * Set the translation domain.
     *
     * @param string $translationDomain The translation domain
     *
     * @return static
     */
    public function setTranslationDomain(?string $translationDomain);

    /**
     * Get the placeholder.
     */
    public function getPlaceholder(): ?string;

    /**
     * Set the placeholder.
     *
     * @param string $placeholder The placeholder
     *
     * @return static
     */
    public function setPlaceholder(?string $placeholder);

    /**
     * Returns an array of resources loaded to build this choice.
     *
     * @return ResourceInterface[] An array of resources
     */
    public function getResources(): array;

    /**
     * Adds a resource for this choice. If the resource already exists
     * it is not added.
     *
     * @param ResourceInterface $resource The resource instance
     */
    public function addResource(ResourceInterface $resource);

    /**
     * Merge the choice builder.
     *
     * @param ChoiceBuilderInterface $builder The new choice builder
     *
     * @return static
     */
    public function merge(ChoiceBuilderInterface $builder);

    /**
     * Build the choice.
     */
    public function build(): ChoiceInterface;
}
