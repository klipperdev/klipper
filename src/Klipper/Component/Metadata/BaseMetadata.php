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
    protected string $name;

    protected string $type;

    protected string $label;

    protected ?string $description;

    protected ?string $translationDomain;

    protected bool $public;

    protected ?string $formType = null;

    protected array $formOptions = [];

    /**
     * @var string[]
     */
    protected array $groups = [];

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

    public function getName(): string
    {
        return $this->name;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function getLabel(): string
    {
        return $this->label;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function getTranslationDomain(): ?string
    {
        return $this->translationDomain;
    }

    public function isPublic(): bool
    {
        return $this->public;
    }

    public function getFormType(): ?string
    {
        return $this->formType;
    }

    public function getFormOptions(): array
    {
        return $this->formOptions;
    }

    public function getGroups(): array
    {
        return $this->groups;
    }
}
