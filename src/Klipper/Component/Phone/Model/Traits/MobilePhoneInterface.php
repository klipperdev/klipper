<?php

/*
 * This file is part of the Klipper package.
 *
 * (c) François Pluchino <francois.pluchino@klipper.dev>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Klipper\Component\Phone\Model\Traits;

use libphonenumber\PhoneNumber;

/**
 * Interface of mobile phone model.
 *
 * @author François Pluchino <francois.pluchino@klipper.dev>
 */
interface MobilePhoneInterface
{
    /**
     * Set the mobile phone.
     *
     * @param null|PhoneNumber $mobilePhone The mobile phone
     *
     * @return static
     */
    public function setMobilePhone(?PhoneNumber $mobilePhone = null);

    /**
     * Get the mobile phone.
     */
    public function getMobilePhone(): ?PhoneNumber;
}
