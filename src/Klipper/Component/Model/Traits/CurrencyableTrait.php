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
 * Trait of curencyable model.
 *
 * @author François Pluchino <francois.pluchino@klipper.dev>
 */
trait CurrencyableTrait
{
    /**
     * @ORM\Column(type="string", length=3, nullable=true)
     *
     * @Assert\Length(max=3)
     * @Assert\Choice(callback={"Klipper\Component\User\Choice\CurrencyAvailable", "getValues"})
     *
     * @Serializer\Expose
     */
    protected ?string $currency = null;

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
