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
interface ChildMetadataInterface extends MetadataInterface
{
    /**
     * Get object metadata parent.
     */
    public function getParent(): ObjectMetadataInterface;

    /**
     * Check if the child is read only.
     */
    public function isReadOnly(): bool;

    /**
     * Check if the child is required.
     */
    public function isRequired(): bool;

    /**
     * Get the input type.
     */
    public function getInput(): ?string;

    /**
     * Get the config of input.
     */
    public function getInputConfig(): ?array;
}
