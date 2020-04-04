<?php

/*
 * This file is part of the Klipper package.
 *
 * (c) François Pluchino <francois.pluchino@klipper.dev>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Klipper\Component\Metadata\Guess;

use Klipper\Component\Metadata\AssociationMetadataBuilderInterface;

/**
 * @author François Pluchino <francois.pluchino@klipper.dev>
 */
interface GuessAssociationConfigInterface extends GuessChildConfigInterface
{
    /**
     * Guess the configuration of the metadata builder.
     *
     * @param AssociationMetadataBuilderInterface $builder The association metadata builder
     */
    public function guessAssociationConfig(AssociationMetadataBuilderInterface $builder);
}
