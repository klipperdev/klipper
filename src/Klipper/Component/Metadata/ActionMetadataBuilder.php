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

use Klipper\Component\Metadata\Exception\BadMethodCallException;
use Klipper\Component\Metadata\Util\BuilderUtil;

/**
 * @author François Pluchino <francois.pluchino@klipper.dev>
 */
class ActionMetadataBuilder implements ActionMetadataBuilderInterface
{
    /**
     * @var null|ObjectMetadataBuilderInterface
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
     * @param string $name The action name
     */
    public function __construct(string $name)
    {
        $this->name = $name;
    }

    /**
     * {@inheritdoc}
     */
    public function setParent(ObjectMetadataBuilderInterface $parent): self
    {
        $this->parent = $parent;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getParent(): ObjectMetadataBuilderInterface
    {
        if (null === $this->parent) {
            throw new BadMethodCallException('Metadata builder getParent method cannot be accessed if the action is not added in the object metadata');
        }

        return $this->parent;
    }

    /**
     * {@inheritdoc}
     */
    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
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
    public function setMethods(array $methods): self
    {
        $this->methods = $methods;

        return $this;
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
    public function setSchemes(array $schemes): self
    {
        $this->schemes = $schemes;

        return $this;
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
    public function setHost(?string $host): self
    {
        $this->host = $host;

        return $this;
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
    public function setPath(?string $path): self
    {
        $this->path = $path;

        return $this;
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
    public function setFragment(?string $fragment): self
    {
        $this->fragment = $fragment;

        return $this;
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
    public function setController(?string $controller): self
    {
        if (null === $controller) {
            unset($this->defaults['_controller']);
        } else {
            $this->defaults['_controller'] = $controller;
        }

        return $this;
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
    public function setFormat(?string $format): self
    {
        if (null === $format) {
            unset($this->defaults['_format']);
        } else {
            $this->defaults['_format'] = $format;
        }

        return $this;
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
    public function setLocale(?string $locale): self
    {
        if (null === $locale) {
            unset($this->defaults['_locale']);
        } else {
            $this->defaults['_locale'] = $locale;
        }

        return $this;
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
    public function setDefaults(array $defaults): self
    {
        $this->defaults = $defaults;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function addDefaults(array $defaults): self
    {
        $this->defaults = array_merge($this->defaults, $defaults);

        return $this;
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
    public function setRequirements(array $requirements): self
    {
        $this->requirements = $requirements;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function addRequirements(array $requirements): self
    {
        $this->requirements = array_merge($this->requirements, $requirements);

        return $this;
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
    public function setOptions(array $options): self
    {
        $this->options = $options;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function addOptions(array $options): self
    {
        $this->options = array_merge($this->options, $options);

        return $this;
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
    public function setCondition(?string $condition): self
    {
        $this->condition = $condition;

        return $this;
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
    public function setConfigurations(array $configurations): self
    {
        $this->configurations = $configurations;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getConfigurations(): array
    {
        return $this->configurations;
    }

    /**
     * {@inheritdoc}
     */
    public function merge(ActionMetadataBuilderInterface $builder): self
    {
        BuilderUtil::mergeValues($this, $builder);

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function build(ObjectMetadataInterface $parent): ActionMetadataInterface
    {
        return new ActionMetadata(
            $parent,
            $this->getName(),
            $this->getMethods(),
            $this->getSchemes(),
            $this->getHost(),
            $this->getPath(),
            $this->getFragment(),
            $this->getDefaults(),
            $this->getRequirements(),
            $this->getOptions(),
            $this->getCondition(),
            $this->getConfigurations()
        );
    }
}
