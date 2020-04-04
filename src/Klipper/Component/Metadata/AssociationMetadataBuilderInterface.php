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
interface AssociationMetadataBuilderInterface extends ChildMetadataBuilderInterface
{
    /**
     * Get the association name.
     */
    public function getAssociation(): string;

    /**
     * Set the target.
     *
     * @param null|string $target The target
     *
     * @return static
     */
    public function setTarget(?string $target);

    /**
     * Get the association target.
     */
    public function getTarget(): ?string;

    /**
     * Merge the association metadata builder.
     *
     * @param AssociationMetadataBuilderInterface $builder The new association metadata builder
     *
     * @return static
     */
    public function merge(AssociationMetadataBuilderInterface $builder);

    /**
     * Build the association metadata.
     *
     * @param ObjectMetadataInterface $parent The object metadata parent
     */
    public function build(ObjectMetadataInterface $parent): AssociationMetadataInterface;
}
