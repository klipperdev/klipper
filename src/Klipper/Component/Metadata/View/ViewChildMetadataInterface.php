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
interface ViewChildMetadataInterface
{
    /**
     * Get the name of metadata object child.
     */
    public function getName(): string;

    /**
     * Get the type of metadata object child.
     */
    public function getType(): string;

    /**
     * Get the label of metadata object child.
     */
    public function getLabel(): string;

    /**
     * Get the description of metadata object child.
     */
    public function getDescription(): ?string;

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

    /**
     * Check if the permissions can be configured for this metadata.
     */
    public function hasEditablePermissions(): bool;
}
