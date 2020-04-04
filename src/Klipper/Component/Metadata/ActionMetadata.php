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
    /**
     * @var ObjectMetadataInterface
     */
    protected $parent;

    /**
     * @var string
     */
    protected $name;

    /**
     * @var string[]
     */
    protected $methods = [];

    /**
     * @var string[]
     */
    protected $schemes = [];

    /**
     * @var null|string
     */
    protected $host;

    /**
     * @var null|string
     */
    protected $path;

    /**
     * @var null|string
     */
    protected $fragment;

    /**
     * @var array
     */
    protected $defaults = [];

    /**
     * @var array
     */
    protected $requirements = [];

    /**
     * @var array
     */
    protected $options = [];

    /**
     * @var null|string
     */
    protected $condition;

    /**
     * @var array
     */
    protected $configurations = [];

    /**
     * Constructor.
     *
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

    /**
     * {@inheritdoc}
     */
    public function getParent(): ObjectMetadataInterface
    {
        return $this->parent;
    }

    /**
     * {@inheritdoc}
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * {@inheritdoc}
     */
    public function getMethods(): array
    {
        return $this->methods;
    }

    /**
     * {@inheritdoc}
     */
    public function getSchemes(): array
    {
        return $this->schemes;
    }

    /**
     * {@inheritdoc}
     */
    public function getHost(): ?string
    {
        return $this->host;
    }

    /**
     * {@inheritdoc}
     */
    public function getPath(): ?string
    {
        return $this->path;
    }

    /**
     * {@inheritdoc}
     */
    public function getFragment(): ?string
    {
        return $this->fragment;
    }

    /**
     * {@inheritdoc}
     */
    public function getController(): ?string
    {
        return $this->defaults['_controller'] ?? null;
    }

    /**
     * {@inheritdoc}
     */
    public function getFormat(): ?string
    {
        return $this->defaults['_format'] ?? null;
    }

    /**
     * {@inheritdoc}
     */
    public function getLocale(): ?string
    {
        return $this->defaults['_locale'] ?? null;
    }

    /**
     * {@inheritdoc}
     */
    public function getDefaults(): array
    {
        return $this->defaults;
    }

    /**
     * {@inheritdoc}
     */
    public function getRequirements(): array
    {
        return $this->requirements;
    }

    /**
     * {@inheritdoc}
     */
    public function getOptions(): array
    {
        return $this->options;
    }

    /**
     * {@inheritdoc}
     */
    public function getCondition(): ?string
    {
        return $this->condition;
    }

    /**
     * {@inheritdoc}
     */
    public function getConfigurations(): array
    {
        return $this->configurations;
    }
}
