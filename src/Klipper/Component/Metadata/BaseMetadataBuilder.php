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
    /**
     * @var null|string
     */
    protected $name;

    /**
     * @var string
     */
    protected $type;

    /**
     * @var null|string
     */
    protected $label;

    /**
     * @var null|string
     */
    protected $description;

    /**
     * @var null|string
     */
    protected $translationDomain;

    /**
     * @var null|bool
     */
    protected $public;

    /**
     * @var null|string
     */
    protected $formType;

    /**
     * @var array
     */
    protected $formOptions = [];

    /**
     * @var string[]
     */
    protected $groups = [];

    /**
     * Constructor.
     *
     * @param string $type The type
     */
    public function __construct(?string $type)
    {
        $this->type = $type;
    }

    /**
     * {@inheritdoc}
     */
    public function setName(?string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * {@inheritdoc}
     */
    public function setType(?string $type): self
    {
        $this->type = $type;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getType(): ?string
    {
        return $this->type;
    }

    /**
     * {@inheritdoc}
     */
    public function setLabel(?string $label): self
    {
        $this->label = $label;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getLabel(): ?string
    {
        return $this->label;
    }

    /**
     * {@inheritdoc}
     */
    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * {@inheritdoc}
     */
    public function setTranslationDomain(?string $translationDomain): self
    {
        $this->translationDomain = $translationDomain;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getTranslationDomain(): ?string
    {
        return $this->translationDomain;
    }

    /**
     * {@inheritdoc}
     */
    public function setPublic(?bool $public): self
    {
        $this->public = $public;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function isPublic(): ?bool
    {
        return $this->public;
    }

    /**
     * {@inheritdoc}
     */
    public function setFormType(?string $formTypeType): self
    {
        $this->formType = $formTypeType;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getFormType(): ?string
    {
        return $this->formType;
    }

    /**
     * {@inheritdoc}
     */
    public function setFormOptions(array $options): self
    {
        $this->formOptions = $options;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getFormOptions(): array
    {
        return $this->formOptions;
    }

    /**
     * {@inheritdoc}
     */
    public function setGroups(array $groups): self
    {
        $this->groups = $groups;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getGroups(): array
    {
        return $this->groups;
    }
}
