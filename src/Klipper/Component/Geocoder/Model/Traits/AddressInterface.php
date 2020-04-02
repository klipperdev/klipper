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
interface AddressInterface
{
    /**
     * Set the street.
     *
     * @param null|string $street The street
     *
     * @return static
     */
    public function setStreet(?string $street);

    /**
     * Get the street.
     *
     * @return null|string
     */
    public function getStreet(): ?string;

    /**
     * Set the complement of street.
     *
     * @param null|string $complement The complement of street
     *
     * @return static
     */
    public function setStreetComplement(?string $complement);

    /**
     * Get the complement of street.
     *
     * @return null|string
     */
    public function getStreetComplement(): ?string;

    /**
     * Set the postal code.
     *
     * @param null|string $postalCode The postal code
     *
     * @return static
     */
    public function setPostalCode(?string $postalCode);

    /**
     * Get the postal code.
     *
     * @return null|string
     */
    public function getPostalCode(): ?string;

    /**
     * Set the city.
     *
     * @param null|string $city The city
     *
     * @return static
     */
    public function setCity(?string $city);

    /**
     * Get the city.
     *
     * @return null|string
     */
    public function getCity(): ?string;

    /**
     * Set the state.
     *
     * @param null|string $state The state
     *
     * @return static
     */
    public function setState(?string $state);

    /**
     * Get the state.
     *
     * @return null|string
     */
    public function getState(): ?string;

    /**
     * Set the country.
     *
     * @param null|string $country The country
     *
     * @return static
     */
    public function setCountry(?string $country);

    /**
     * Get the country.
     *
     * @return null|string
     */
    public function getCountry(): ?string;

    /**
     * Set the geometry location of address.
     *
     * @param null|Point $addressLocation The geometry location of address
     *
     * @return static
     */
    public function setAddressLocation(?Point $addressLocation);

    /**
     * Get the geometry location of address.
     *
     * @return null|Point
     */
    public function getAddressLocation(): ?Point;
}
