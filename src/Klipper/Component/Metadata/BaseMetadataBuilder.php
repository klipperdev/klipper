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
abstract class BaseMetadataBuilder implements MetadataBuilderInterface
{
    protected ?string $name = null;

    protected ?string $type;

    protected ?string $label = null;

    protected ?string $description = null;

    protected ?string $translationDomain = null;

    protected ?bool $public = null;

    protected ?string $formType = null;

    protected array $formOptions = [];

    /**
     * @var string[]
     */
    protected array $groups = [];

    /**
     * @param string $type The type
     */
    public function __construct(?string $type)
    {
        $this->type = $type;
    }

    public function setName(?string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setType(?string $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setLabel(?string $label): self
    {
        $this->label = $label;

        return $this;
    }

    public function getLabel(): ?string
    {
        return $this->label;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setTranslationDomain(?string $translationDomain): self
    {
        $this->translationDomain = $translationDomain;

        return $this;
    }

    public function getTranslationDomain(): ?string
    {
        return $this->translationDomain;
    }

    public function setPublic(?bool $public): self
    {
        $this->public = $public;

        return $this;
    }

    public function isPublic(): ?bool
    {
        return $this->public;
    }

    public function setFormType(?string $formTypeType): self
    {
        $this->formType = $formTypeType;

        return $this;
    }

    public function getFormType(): ?string
    {
        return $this->formType;
    }

    public function setFormOptions(array $options): self
    {
        $this->formOptions = $options;

        return $this;
    }

    public function getFormOptions(): array
    {
        return $this->formOptions;
    }

    public function setGroups(array $groups): self
    {
        $this->groups = $groups;

        return $this;
    }

    public function getGroups(): array
    {
        return $this->groups;
    }
}
