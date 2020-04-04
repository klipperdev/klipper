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
interface ChoiceInterface
{
    /**
     * Get the unique name of the choice.
     */
    public function getName(): string;

    /**
     * Get the list of choice.
     */
    public function getListIdentifiers(): array;

    /**
     * Get all values.
     *
     * @return string[]
     */
    public function getValues(): array;

    /**
     * Get the translation domain name.
     */
    public function getTranslationDomain(): ?string;

    /**
     * Get the placeholder.
     */
    public function getPlaceholder(): ?string;

    /**
     * Returns an array of resources loaded to build this choice.
     *
     * @return ResourceInterface[] An array of resources
     */
    public function getResources(): array;
}
