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
interface ActionMetadataInterface
{
    /**
     * Get object metadata parent.
     */
    public function getParent(): ObjectMetadataInterface;

    /**
     * Get the name.
     */
    public function getName(): string;

    /**
     * Get the methods.
     *
     * @return string[]
     */
    public function getMethods(): array;

    /**
     * Get the host.
     *
     * @return string[]
     */
    public function getSchemes(): array;

    /**
     * Get the host.
     */
    public function getHost(): ?string;

    /**
     * Get the path.
     */
    public function getPath(): ?string;

    /**
     * Get the fragment.
     */
    public function getFragment(): ?string;

    /**
     * Get the controller.
     */
    public function getController(): ?string;

    /**
     * Get the format.
     */
    public function getFormat(): ?string;

    /**
     * Get the locale.
     */
    public function getLocale(): ?string;

    /**
     * Get the defaults.
     */
    public function getDefaults(): array;

    /**
     * Get the requirements.
     */
    public function getRequirements(): array;

    /**
     * Get the options.
     */
    public function getOptions(): array;

    /**
     * Get the condition.
     */
    public function getCondition(): ?string;

    /**
     * Get the extra configurations.
     */
    public function getConfigurations(): array;
}
