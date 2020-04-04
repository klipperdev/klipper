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

/**
 * @author François Pluchino <francois.pluchino@klipper.dev>
 */
interface MetadataBuilderInterface
{
    /**
     * Get the unique name.
     */
    public function getName(): ?string;

    /**
     * Get the type.
     */
    public function getType(): ?string;

    /**
     * Get the label.
     */
    public function getLabel(): ?string;

    /**
     * Get the description.
     */
    public function getDescription(): ?string;

    /**
     * Get the translation domain.
     */
    public function getTranslationDomain(): ?string;

    /**
     * Check if the metadata is public.
     */
    public function isPublic(): ?bool;

    /**
     * Set the unique name.
     *
     * @param string $name The unique name
     *
     * @return static
     */
    public function setName(?string $name);

    /**
     * Set the type.
     *
     * @param null|string $type The type
     *
     * @return static
     */
    public function setType(?string $type);

    /**
     * Set the label.
     *
     * @param string $label The label
     *
     * @return static
     */
    public function setLabel(?string $label);

    /**
     * Set the description.
     *
     * @param null|string $description The description
     *
     * @return static
     */
    public function setDescription(?string $description);

    /**
     * Set the translation domain.
     *
     * @param null|string $translationDomain The translation domain
     *
     * @return static
     */
    public function setTranslationDomain(?string $translationDomain);

    /**
     * Set if the metadata is public.
     *
     * @param null|bool $public Check if the metadata is public
     *
     * @return static
     */
    public function setPublic(?bool $public);

    /**
     * Set the form type.
     *
     * @param null|string $formType The form type
     *
     * @return static
     */
    public function setFormType(?string $formType);

    /**
     * Get the form type.
     */
    public function getFormType(): ?string;

    /**
     * Set the form options.
     *
     * @param array $options The form options
     *
     * @return static
     */
    public function setFormOptions(array $options);

    /**
     * Get the form options.
     */
    public function getFormOptions(): array;

    /**
     * Set the groups.
     *
     * @param string[] $groups The groups
     *
     * @return static
     */
    public function setGroups(array $groups);

    /**
     * Get the groups.
     *
     * @return string[]
     */
    public function getGroups(): array;
}
