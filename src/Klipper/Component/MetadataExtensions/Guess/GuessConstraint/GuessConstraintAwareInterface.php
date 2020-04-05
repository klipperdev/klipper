<?php

/*
 * This file is part of the Klipper package.
 *
 * (c) François Pluchino <francois.pluchino@klipper.dev>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Klipper\Component\MetadataExtensions\Guess\GuessConstraint;

use Klipper\Component\MetadataExtensions\Guess\GuessSymfonyConstraint;

/**
 * @author François Pluchino <francois.pluchino@klipper.dev>
 */
interface GuessConstraintAwareInterface extends GuessConstraintInterface
{
    public function setGuesser(GuessSymfonyConstraint $guesser): void;
}
