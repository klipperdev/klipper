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
class AssociationMetadata extends BaseChildMetadata implements AssociationMetadataInterface
{
    /**
     * @var string
     */
    protected $association;

    /**
     * @var string
     */
    protected $target;

    /**
     * Constructor.
     */
    public function __construct(
        ObjectMetadataInterface $parent,
        string $association,
        string $name,
        string $type,
        string $target,
        string $label,
        ?string $description,
        ?string $translationDomain,
        bool $public,
        bool $readOnly,
        bool $required,
        ?string $input,
        ?array $inputConfig,
        ?string $formType,
        array $formOptions,
        array $groups
    ) {
        parent::__construct(
            $name,
            $type,
            $label,
            $description,
            $translationDomain,
            $public
        );

        $this->parent = $parent;
        $this->association = $association;
        $this->target = $target;

        $this->readOnly = $readOnly;
        $this->required = $required;
        $this->input = $input;
        $this->inputConfig = $inputConfig;
        $this->formType = $formType;
        $this->formOptions = $formOptions;
        $this->groups = $groups;
    }

    /**
     * {@inheritdoc}
     */
    public function getAssociation(): string
    {
        return $this->association;
    }

    /**
     * {@inheritdoc}
     */
    public function getTarget(): string
    {
        return $this->target;
    }
}
