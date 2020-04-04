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
interface MetadataInterface
{
    /**
     * Get the unique name.
     */
    public function getName(): string;

    /**
     * Get the type.
     */
    public function getType(): string;

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
    public function isPublic(): bool;

    /**
     * Get the form type.
     */
    public function getFormType(): ?string;

    /**
     * Get the form options.
     */
    public function getFormOptions(): array;

    /**
     * Get the groups.
     *
     * @return string[]
     */
    public function getGroups(): array;
}
