<?php

/*
 * This file is part of the Klipper package.
 *
 * (c) François Pluchino <francois.pluchino@klipper.dev>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Klipper\Component\Metadata\View;

/**
 * @author François Pluchino <francois.pluchino@klipper.dev>
 */
interface ViewChoiceInterface
{
    /**
     * Get the choice name.
     */
    public function getName(): string;

    /**
     * Get the translated identifiers.
     */
    public function getIdentifiers(): array;

    /**
     * Get the placeholder.
     */
    public function getPlaceholder(): ?string;
}
