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
interface ActionMetadataBuilderInterface
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
     * Set the name.
     *
     * @param string $name The name
     *
     * @return static
     */
    public function setName(string $name);

    /**
     * Get the name.
     */
    public function getName(): string;

    /**
     * Set the methods.
     *
     * @param string[] $methods The methods
     *
     * @return static
     */
    public function setMethods(array $methods);

    /**
     * Get the methods.
     *
     * @return string[]
     */
    public function getMethods(): array;

    /**
     * Set the schemes.
     *
     * @param string[] $schemes The schemes
     *
     * @return static
     */
    public function setSchemes(array $schemes);

    /**
     * Get the host.
     *
     * @return string[]
     */
    public function getSchemes(): array;

    /**
     * Set the host.
     *
     * @param string $host The host
     *
     * @return static
     */
    public function setHost(?string $host);

    /**
     * Get the host.
     */
    public function getHost(): ?string;

    /**
     * Set the path.
     *
     * @param string $path The path
     *
     * @return static
     */
    public function setPath(?string $path);

    /**
     * Get the path.
     */
    public function getPath(): ?string;

    /**
     * Set the fragment.
     *
     * @param string $fragment The fragment
     *
     * @return static
     */
    public function setFragment(?string $fragment);

    /**
     * Get the fragment.
     */
    public function getFragment(): ?string;

    /**
     * Set the controller.
     *
     * @param string $controller The controller
     *
     * @return static
     */
    public function setController(?string $controller);

    /**
     * Get the controller.
     */
    public function getController(): ?string;

    /**
     * Set the format.
     *
     * @param string $format The format
     *
     * @return static
     */
    public function setFormat(?string $format);

    /**
     * Get the format.
     */
    public function getFormat(): ?string;

    /**
     * Set the locale.
     *
     * @param string $locale The locale
     *
     * @return static
     */
    public function setLocale(?string $locale);

    /**
     * Get the locale.
     */
    public function getLocale(): ?string;

    /**
     * Set the defaults.
     *
     * @param array $defaults The defaults
     *
     * @return static
     */
    public function setDefaults(array $defaults);

    /**
     * Add the defaults.
     *
     * @param array $defaults The defaults
     *
     * @return static
     */
    public function addDefaults(array $defaults);

    /**
     * Get the defaults.
     */
    public function getDefaults(): array;

    /**
     * Set the requirements.
     *
     * @param array $requirements The requirements
     *
     * @return static
     */
    public function setRequirements(array $requirements);

    /**
     * Add the requirements.
     *
     * @param array $requirements The requirements
     *
     * @return static
     */
    public function addRequirements(array $requirements);

    /**
     * Get the requirements.
     */
    public function getRequirements(): array;

    /**
     * Set the options.
     *
     * @param array $options The options
     *
     * @return static
     */
    public function setOptions(array $options);

    /**
     * Add the options.
     *
     * @param array $options The options
     *
     * @return static
     */
    public function addOptions(array $options);

    /**
     * Get the options.
     */
    public function getOptions(): array;

    /**
     * Set the condition.
     *
     * @param string $condition The condition
     *
     * @return static
     */
    public function setCondition(?string $condition);

    /**
     * Get the condition.
     */
    public function getCondition(): ?string;

    /**
     * Set the extra configurations.
     *
     * @param array $configurations The configurations
     *
     * @return static
     */
    public function setConfigurations(array $configurations);

    /**
     * Get the extra configurations.
     */
    public function getConfigurations(): array;

    /**
     * Merge the action metadata builder.
     *
     * @param ActionMetadataBuilderInterface $builder The new action metadata builder
     *
     * @return static
     */
    public function merge(ActionMetadataBuilderInterface $builder);

    /**
     * Build the action metadata.
     *
     * @param ObjectMetadataInterface $parent The object metadata parent
     */
    public function build(ObjectMetadataInterface $parent): ActionMetadataInterface;
}
