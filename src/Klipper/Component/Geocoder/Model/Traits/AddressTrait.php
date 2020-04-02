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
    /**
     * @var null|string
     */
    protected $street;

    /**
     * @var null|string
     */
    protected $streetComplement;

    /**
     * @var null|string
     */
    protected $postalCode;

    /**
     * @var null|string
     */
    protected $city;

    /**
     * @var null|string
     */
    protected $state;

    /**
     * @var null|string
     */
    protected $country;

    /**
     * @var null|Point
     */
    protected $addressLocation;

    /**
     * {@inheritdoc}
     */
    public function setStreet(?string $street): self
    {
        $this->street = $street;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getStreet(): ?string
    {
        return $this->street;
    }

    /**
     * {@inheritdoc}
     */
    public function setStreetComplement(?string $complement): self
    {
        $this->streetComplement = $complement;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getStreetComplement(): ?string
    {
        return $this->streetComplement;
    }

    /**
     * {@inheritdoc}
     */
    public function setPostalCode(?string $postalCode): self
    {
        $this->postalCode = $postalCode;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getPostalCode(): ?string
    {
        return $this->postalCode;
    }

    /**
     * {@inheritdoc}
     */
    public function setCity(?string $city): self
    {
        $this->city = $city;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getCity(): ?string
    {
        return $this->city;
    }

    /**
     * {@inheritdoc}
     */
    public function setState(?string $state): self
    {
        $this->state = $state;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getState(): ?string
    {
        return $this->state;
    }

    /**
     * {@inheritdoc}
     */
    public function setCountry(?string $country): self
    {
        $this->country = $country;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getCountry(): ?string
    {
        return $this->country;
    }

    /**
     * {@inheritdoc}
     */
    public function setAddressLocation(?Point $addressLocation): self
    {
        if (null === $addressLocation || $addressLocation instanceof Point) {
            $this->addressLocation = $addressLocation;
        }

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getAddressLocation(): ?Point
    {
        return $this->addressLocation;
    }
}
