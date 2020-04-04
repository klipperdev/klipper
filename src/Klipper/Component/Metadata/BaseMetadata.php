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
abstract class BaseMetadata implements MetadataInterface
{
    /**
     * @var string
     */
    protected $name;

    /**
     * @var string
     */
    protected $type;

    /**
     * @var string
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
     * @var bool
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
     * @param string      $name              The unique name
     * @param string      $type              The type
     * @param string      $label             The label
     * @param null|string $description       The description
     * @param null|string $translationDomain The translation domain
     * @param bool        $public            Check if the metadata is public
     */
    public function __construct(
        string $name,
        string $type,
        string $label,
        ?string $description = null,
        ?string $translationDomain = null,
        bool $public = false
    ) {
        $this->name = $name;
        $this->type = $type;
        $this->label = $label;
        $this->description = $description;
        $this->translationDomain = $translationDomain;
        $this->public = $public;
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
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * {@inheritdoc}
     */
    public function getLabel(): string
    {
        return $this->label;
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
    public function getTranslationDomain(): ?string
    {
        return $this->translationDomain;
    }

    /**
     * {@inheritdoc}
     */
    public function isPublic(): bool
    {
        return $this->public;
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
    public function getFormOptions(): array
    {
        return $this->formOptions;
    }

    /**
     * {@inheritdoc}
     */
    public function getGroups(): array
    {
        return $this->groups;
    }
}
