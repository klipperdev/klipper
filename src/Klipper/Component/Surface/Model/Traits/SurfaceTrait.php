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
 * Trait of surface model.
 *
 * @author François Pluchino <francois.pluchino@klipper.dev>
 */
trait SurfaceTrait
{
    /**
     * @var null|float|int
     */
    protected $surface;

    protected ?string $surfaceUnit = null;

    /**
     * @param mixed $surface
     */
    public function setSurface($surface): self
    {
        $this->surface = \is_int($surface) ? (float) $surface : $surface;

        return $this;
    }

    public function getSurface(): ?float
    {
        return $this->surface;
    }

    public function setSurfaceUnit(?string $surfaceUnit): self
    {
        $this->surfaceUnit = $surfaceUnit;

        return $this;
    }

    public function getSurfaceUnit(): ?string
    {
        return $this->surfaceUnit;
    }
}
