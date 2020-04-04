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
interface AssociationMetadataInterface extends ChildMetadataInterface
{
    /**
     * Get the association name.
     */
    public function getAssociation(): string;

    /**
     * Get the target.
     */
    public function getTarget(): string;
}
