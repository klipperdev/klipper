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
class ViewAssociationMetadata extends BaseViewChildMetadata implements ViewAssociationMetadataInterface
{
    protected string $association;

    protected string $target;

    protected bool $masterDetails;

    /**
     * @param string      $association         The association name in class
     * @param string      $name                The name of metadata association
     * @param string      $type                The type of metadata association
     * @param string      $target              The target of metadata association
     * @param string      $label               The label of metadata association
     * @param null|string $description         The description of metadata association
     * @param bool        $readOnly            Check if the metadata is read only
     * @param bool        $required            Check if the metadata is required
     * @param null|string $input               The input type
     * @param null|array  $inputConfig         The config of input type
     * @param bool        $editablePermissions Check if the permissions can be configured for this metadata
     * @param bool        $masterDetails       Check if the association is a master-details association
     */
    public function __construct(
        string $association,
        string $name,
        string $type,
        string $target,
        string $label,
        ?string $description,
        bool $readOnly,
        bool $required,
        ?string $input,
        ?array $inputConfig,
        bool $editablePermissions = false,
        bool $masterDetails = false
    ) {
        parent::__construct($name, $type, $label, $description, $readOnly, $required, $input, $inputConfig, $editablePermissions);

        $this->association = $association;
        $this->target = $target;
        $this->masterDetails = $masterDetails;
    }

    public function getAssociation(): string
    {
        return $this->association;
    }

    public function getTarget(): string
    {
        return $this->target;
    }

    public function isMasterDetails(): bool
    {
        return $this->masterDetails;
    }
}
