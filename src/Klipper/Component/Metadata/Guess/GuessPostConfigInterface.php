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

use Klipper\Component\Metadata\ObjectMetadataBuilderInterface;

/**
 * @author François Pluchino <francois.pluchino@klipper.dev>
 */
interface GuessPostConfigInterface extends GuessConfigInterface
{
    /**
     * Guess the configuration of the metadata builder.
     *
     * @param ObjectMetadataBuilderInterface $builder The object metadata builder
     */
    public function guessPostConfig(ObjectMetadataBuilderInterface $builder);
}
