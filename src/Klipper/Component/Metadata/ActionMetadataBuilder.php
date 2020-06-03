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
    protected ?ObjectMetadataBuilderInterface $parent = null;

    protected string $name;

    /**
     * @var string[]
     */
    protected array $methods = [];

    /**
     * @var string[]
     */
    protected array $schemes = [];

    protected ?string $host = null;

    protected ?string $path = null;

    protected ?string $fragment = null;

    protected array $defaults = [];

    protected array $requirements = [];

    protected array $options = [];

    protected ?string $condition = null;

    protected array $configurations = [];

    /**
     * @param string $name The action name
     */
    public function __construct(string $name)
    {
        $this->name = $name;
    }

    public function setParent(ObjectMetadataBuilderInterface $parent): self
    {
        $this->parent = $parent;

        return $this;
    }

    public function getParent(): ObjectMetadataBuilderInterface
    {
        if (null === $this->parent) {
            throw new BadMethodCallException('Metadata builder getParent method cannot be accessed if the action is not added in the object metadata');
        }

        return $this->parent;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setMethods(array $methods): self
    {
        $this->methods = $methods;

        return $this;
    }

    public function getMethods(): array
    {
        return $this->methods;
    }

    public function setSchemes(array $schemes): self
    {
        $this->schemes = $schemes;

        return $this;
    }

    public function getSchemes(): array
    {
        return $this->schemes;
    }

    public function setHost(?string $host): self
    {
        $this->host = $host;

        return $this;
    }

    public function getHost(): ?string
    {
        return $this->host;
    }

    public function setPath(?string $path): self
    {
        $this->path = $path;

        return $this;
    }

    public function getPath(): ?string
    {
        return $this->path;
    }

    public function setFragment(?string $fragment): self
    {
        $this->fragment = $fragment;

        return $this;
    }

    public function getFragment(): ?string
    {
        return $this->fragment;
    }

    public function setController(?string $controller): self
    {
        if (null === $controller) {
            unset($this->defaults['_controller']);
        } else {
            $this->defaults['_controller'] = $controller;
        }

        return $this;
    }

    public function getController(): ?string
    {
        return $this->defaults['_controller'] ?? null;
    }

    public function setFormat(?string $format): self
    {
        if (null === $format) {
            unset($this->defaults['_format']);
        } else {
            $this->defaults['_format'] = $format;
        }

        return $this;
    }

    public function getFormat(): ?string
    {
        return $this->defaults['_format'] ?? null;
    }

    public function setLocale(?string $locale): self
    {
        if (null === $locale) {
            unset($this->defaults['_locale']);
        } else {
            $this->defaults['_locale'] = $locale;
        }

        return $this;
    }

    public function getLocale(): ?string
    {
        return $this->defaults['_locale'] ?? null;
    }

    public function setDefaults(array $defaults): self
    {
        $this->defaults = $defaults;

        return $this;
    }

    public function addDefaults(array $defaults): self
    {
        $this->defaults = array_merge($this->defaults, $defaults);

        return $this;
    }

    public function getDefaults(): array
    {
        return $this->defaults;
    }

    public function setRequirements(array $requirements): self
    {
        $this->requirements = $requirements;

        return $this;
    }

    public function addRequirements(array $requirements): self
    {
        $this->requirements = array_merge($this->requirements, $requirements);

        return $this;
    }

    public function getRequirements(): array
    {
        return $this->requirements;
    }

    public function setOptions(array $options): self
    {
        $this->options = $options;

        return $this;
    }

    public function addOptions(array $options): self
    {
        $this->options = array_merge($this->options, $options);

        return $this;
    }

    public function getOptions(): array
    {
        return $this->options;
    }

    public function setCondition(?string $condition): self
    {
        $this->condition = $condition;

        return $this;
    }

    public function getCondition(): ?string
    {
        return $this->condition;
    }

    public function setConfigurations(array $configurations): self
    {
        $this->configurations = $configurations;

        return $this;
    }

    public function getConfigurations(): array
    {
        return $this->configurations;
    }

    public function merge(ActionMetadataBuilderInterface $builder): self
    {
        BuilderUtil::mergeValues($this, $builder);

        return $this;
    }

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
