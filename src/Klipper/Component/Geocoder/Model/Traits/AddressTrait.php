<?php

/*
 * This file is part of the Klipper package.
 *
 * (c) François Pluchino <francois.pluchino@klipper.dev>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Klipper\Component\Geocoder\Model\Traits;

use CrEOF\Spatial\PHP\Types\Geometry\Point;

/**
 * Trait of address.
 *
 * @author François Pluchino <francois.pluchino@klipper.dev>
 */
trait AddressTrait
{
    protected ?string $street = null;

    protected ?string $streetComplement = null;

    protected ?string $postalCode = null;

    protected ?string $city = null;

    protected ?string $state = null;

    protected ?string $country = null;

    protected ?Point $addressLocation = null;

    public function setStreet(?string $street): self
    {
        $this->street = $street;

        return $this;
    }

    public function getStreet(): ?string
    {
        return $this->street;
    }

    public function setStreetComplement(?string $complement): self
    {
        $this->streetComplement = $complement;

        return $this;
    }

    public function getStreetComplement(): ?string
    {
        return $this->streetComplement;
    }

    public function setPostalCode(?string $postalCode): self
    {
        $this->postalCode = $postalCode;

        return $this;
    }

    public function getPostalCode(): ?string
    {
        return $this->postalCode;
    }

    public function setCity(?string $city): self
    {
        $this->city = $city;

        return $this;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setState(?string $state): self
    {
        $this->state = $state;

        return $this;
    }

    public function getState(): ?string
    {
        return $this->state;
    }

    public function setCountry(?string $country): self
    {
        $this->country = $country;

        return $this;
    }

    public function getCountry(): ?string
    {
        return $this->country;
    }

    public function setAddressLocation(?Point $addressLocation): self
    {
        if (null === $addressLocation || $addressLocation instanceof Point) {
            $this->addressLocation = $addressLocation;
        }

        return $this;
    }

    public function getAddressLocation(): ?Point
    {
        return $this->addressLocation;
    }
}
