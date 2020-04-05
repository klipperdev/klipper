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
 * Trait of mobile phone model.
 *
 * @author François Pluchino <francois.pluchino@klipper.dev>
 */
trait MobilePhoneTrait
{
    /**
     * @var null|PhoneNumber
     */
    protected $mobilePhone;

    /**
     * {@inheritdoc}
     */
    public function setMobilePhone(?PhoneNumber $mobilePhone = null): self
    {
        $this->mobilePhone = $mobilePhone;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getMobilePhone(): ?PhoneNumber
    {
        return $this->mobilePhone;
    }
}
