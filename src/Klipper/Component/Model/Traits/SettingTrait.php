<?php

/*
 * This file is part of the Klipper package.
 *
 * (c) François Pluchino <francois.pluchino@klipper.dev>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Klipper\Component\Model\Traits;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Trait of setting.
 *
 * @author François Pluchino <francois.pluchino@klipper.dev>
 */
trait SettingTrait
{
    /**
     * @ORM\Column(type="string", nullable=true)
     * @Assert\Choice(callback={"DateTimeZone", "listIdentifiers"})
     * @Serializer\Expose
     */
    protected ?string $timezone = null;

    /**
     * @ORM\Column(type="string", length=5, nullable=true)
     * @Assert\Choice(callback={"Klipper\Component\User\Choice\LocaleAvailable", "getValues"})
     * @Serializer\Expose
     */
    protected ?string $locale = null;

    /**
     * @ORM\Column(type="string", length=3, nullable=true)
     * @Assert\Choice(callback={"Klipper\Component\User\Choice\CurrencyAvailable", "getValues"})
     * @Serializer\Expose
     */
    protected ?string $currency = null;

    public function setTimezone(?string $timezone): self
    {
        $this->timezone = $timezone;

        return $this;
    }

    public function getTimezone(): ?string
    {
        return $this->timezone;
    }

    public function setLocale(?string $locale): self
    {
        $this->locale = $locale;

        return $this;
    }

    public function getLocale(): ?string
    {
        return $this->locale;
    }

    public function setCurrency(?string $currency): self
    {
        $this->currency = $currency;

        return $this;
    }

    public function getCurrency(): ?string
    {
        return $this->currency;
    }
}
