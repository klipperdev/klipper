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
class ActionMetadata implements ActionMetadataInterface
{
    protected ObjectMetadataInterface $parent;

    protected string $name;

    /**
     * @var string[]
     */
    protected array $methods = [];

    /**
     * @var string[]
     */
    protected array $schemes = [];

    protected ?string $host;

    protected ?string $path;

    protected ?string $fragment;

    protected array $defaults = [];

    protected array $requirements = [];

    protected array $options = [];

    protected ?string $condition;

    protected array $configurations = [];

    /**
     * @param ObjectMetadataInterface $parent         The object metadata parent
     * @param string                  $name           The action name
     * @param array                   $methods        The route methods
     * @param array                   $schemes        The route schemes
     * @param null|string             $host           The route host
     * @param null|string             $path           The route path
     * @param null|string             $fragment       The route fragment
     * @param array                   $defaults       The route default
     * @param array                   $requirements   The route requirements
     * @param array                   $options        The route options
     * @param null|string             $condition      The route condition
     * @param array                   $configurations The extra configurations
     */
    public function __construct(
        ObjectMetadataInterface $parent,
        string $name,
        array $methods,
        array $schemes,
        ?string $host,
        ?string $path,
        ?string $fragment,
        array $defaults,
        array $requirements,
        array $options,
        ?string $condition,
        array $configurations = []
    ) {
        $this->parent = $parent;
        $this->name = $name;
        $this->methods = $methods;
        $this->schemes = $schemes;
        $this->host = $host;
        $this->path = $path;
        $this->fragment = $fragment;
        $this->defaults = $defaults;
        $this->requirements = $requirements;
        $this->options = $options;
        $this->condition = $condition;
        $this->configurations = $configurations;
    }

    public function getParent(): ObjectMetadataInterface
    {
        return $this->parent;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getMethods(): array
    {
        return $this->methods;
    }

    public function getSchemes(): array
    {
        return $this->schemes;
    }

    public function getHost(): ?string
    {
        return $this->host;
    }

    public function getPath(): ?string
    {
        return $this->path;
    }

    public function getFragment(): ?string
    {
        return $this->fragment;
    }

    public function getController(): ?string
    {
        return $this->defaults['_controller'] ?? null;
    }

    public function getFormat(): ?string
    {
        return $this->defaults['_format'] ?? null;
    }

    public function getLocale(): ?string
    {
        return $this->defaults['_locale'] ?? null;
    }

    public function getDefaults(): array
    {
        return $this->defaults;
    }

    public function getRequirements(): array
    {
        return $this->requirements;
    }

    public function getOptions(): array
    {
        return $this->options;
    }

    public function getCondition(): ?string
    {
        return $this->condition;
    }

    public function getConfigurations(): array
    {
        return $this->configurations;
    }
}
