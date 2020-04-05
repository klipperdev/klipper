<?php

/*
 * This file is part of the Klipper package.
 *
 * (c) François Pluchino <francois.pluchino@klipper.dev>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Klipper\Component\Content\Uploader\Namer;

/**
 * @author François Pluchino <francois.pluchino@klipper.dev>
 */
interface NamerManagerInterface
{
    /**
     * Check if the namer is present.
     *
     * @param string $name The namer file name
     */
    public function has(?string $name): bool;

    /**
     * Get the namer.
     *
     * @param string $name The name
     */
    public function get(?string $name): ?NamerInterface;

    /**
     * Add the namer.
     *
     * @param NamerInterface $namer The namer
     *
     * @return static
     */
    public function add(NamerInterface $namer);
}
