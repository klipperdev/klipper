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
interface ChildMetadataBuilderInterface extends MetadataBuilderInterface
{
    /**
     * Set the object metadata builder parent.
     *
     * @param ObjectMetadataBuilderInterface $parent The object metadata builder parent
     *
     * @return static
     *
     * @internal
     */
    public function setParent(ObjectMetadataBuilderInterface $parent);

    /**
     * Get object metadata builder parent.
     */
    public function getParent(): ObjectMetadataBuilderInterface;

    /**
     * Set if the child is read only.
     *
     * @param bool $readOnly Check if the child is read only
     *
     * @return static
     */
    public function setReadOnly(?bool $readOnly);

    /**
     * Check if the child is read only.
     *
     * @return bool
     */
    public function isReadOnly(): ?bool;

    /**
     * Set if the child is required.
     *
     * @param bool $required Check if the child is required
     *
     * @return static
     */
    public function setRequired(?bool $required);

    /**
     * Check if the child is required.
     *
     * @return bool
     */
    public function isRequired(): ?bool;

    /**
     * Set the input type.
     *
     * @param null|string $input The input type
     *
     * @return static
     */
    public function setInput(?string $input);

    /**
     * Get the input type.
     */
    public function getInput(): ?string;

    /**
     * Set the input config.
     *
     * @param null|array $inputConfig The input config
     *
     * @return static
     */
    public function setInputConfig(?array $inputConfig);

    /**
     * Get the config of input.
     */
    public function getInputConfig(): ?array;
}
