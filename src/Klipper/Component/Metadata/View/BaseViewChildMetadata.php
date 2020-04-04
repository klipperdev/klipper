<?php

/*
 * This file is part of the Klipper package.
 *
 * (c) François Pluchino <francois.pluchino@klipper.dev>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Klipper\Component\Metadata\View;

/**
 * @author François Pluchino <francois.pluchino@klipper.dev>
 */
abstract class BaseViewChildMetadata implements ViewChildMetadataInterface
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
     * @var bool
     */
    protected $readOnly;

    /**
     * @var bool
     */
    protected $required;

    /**
     * @var null|string
     */
    protected $input;

    /**
     * @var null|array
     */
    protected $inputConfig;

    /**
     * @var bool
     */
    protected $editablePermissions;

    /**
     * Constructor.
     *
     * @param string      $name                The name of metadata field
     * @param string      $type                The type of metadata field
     * @param string      $label               The label of metadata field
     * @param null|string $description         The description of metadata field
     * @param bool        $readOnly            Check if the metadata is read only
     * @param bool        $required            Check if the metadata is required
     * @param null|string $input               The input type
     * @param null|array  $inputConfig         The config of input type
     * @param bool        $editablePermissions Check if the permissions can be configured for this metadata
     */
    public function __construct(
        string $name,
        string $type,
        string $label,
        ?string $description,
        bool $readOnly,
        bool $required,
        ?string $input,
        ?array $inputConfig,
        bool $editablePermissions = false
    ) {
        $this->name = $name;
        $this->type = $type;
        $this->label = $label;
        $this->description = $description;
        $this->readOnly = $readOnly;
        $this->required = $required;
        $this->input = $input;
        $this->inputConfig = $inputConfig;
        $this->editablePermissions = $editablePermissions;
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
    public function isReadOnly(): bool
    {
        return $this->readOnly;
    }

    /**
     * {@inheritdoc}
     */
    public function isRequired(): bool
    {
        return $this->required;
    }

    /**
     * {@inheritdoc}
     */
    public function getInput(): ?string
    {
        return $this->input;
    }

    /**
     * {@inheritdoc}
     */
    public function getInputConfig(): ?array
    {
        return $this->inputConfig;
    }

    /**
     * {@inheritdoc}
     */
    public function hasEditablePermissions(): bool
    {
        return $this->editablePermissions;
    }
}
