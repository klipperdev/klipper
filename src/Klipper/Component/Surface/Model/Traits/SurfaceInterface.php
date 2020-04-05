<?php

/*
 * This file is part of the Klipper package.
 *
 * (c) François Pluchino <francois.pluchino@klipper.dev>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Klipper\Component\Surface\Model\Traits;

/**
 * Interface of surface model.
 *
 * @author François Pluchino <francois.pluchino@klipper.dev>
 */
interface SurfaceInterface
{
    /**
     * Set the surface.
     *
     * @param null|float|int $surface The surface
     *
     * @return static
     */
    public function setSurface($surface);

    /**
     * Get the surface.
     */
    public function getSurface(): ?float;

    /**
     * Set the surface unit.
     *
     * @param null|string $surfaceUnit The surface unit
     *
     * @return static
     */
    public function setSurfaceUnit(?string $surfaceUnit);

    /**
     * Set the surface unit.
     */
    public function getSurfaceUnit(): ?string;
}
