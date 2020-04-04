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

use Klipper\Component\Metadata\MetadataRegistryInterface;

/**
 * @author François Pluchino <francois.pluchino@klipper.dev>
 */
interface GuessRegistryAwareInterface extends GuessConfigInterface
{
    /**
     * Set the metadata registry in guess.
     *
     * @param MetadataRegistryInterface $registry The registry of metadata
     */
    public function setRegistry(MetadataRegistryInterface $registry);
}
