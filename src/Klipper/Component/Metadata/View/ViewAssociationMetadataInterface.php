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
interface ViewAssociationMetadataInterface extends ViewChildMetadataInterface
{
    /**
     * Get the class association name of metadata object association.
     */
    public function getAssociation(): string;

    /**
     * Get the type of metadata object association.
     */
    public function getTarget(): string;

    /**
     * Check if the association is a master-details association.
     */
    public function isMasterDetails(): bool;
}
