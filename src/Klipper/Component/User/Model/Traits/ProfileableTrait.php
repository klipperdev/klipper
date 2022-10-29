<?php

/*
 * This file is part of the Klipper package.
 *
 * (c) François Pluchino <francois.pluchino@klipper.dev>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Klipper\Component\User\Model\Traits;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Profileable trait.
 *
 * @author François Pluchino <francois.pluchino@klipper.dev>
 */
trait ProfileableTrait
{
    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Assert\Length(max=255)
     * @Serializer\Expose
     * @Serializer\Groups({"Default", "Public"})
     */
    protected ?string $firstName = null;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Assert\Length(max=255)
     * @Serializer\Expose
     * @Serializer\Groups({"Default", "Public"})
     */
    protected ?string $lastName = null;

    /**
     * @ORM\Column(type="string", length=5, nullable=true)
     * @Assert\Length(max=5)
     * @Serializer\Expose
     * @Serializer\ReadOnlyProperty
     * @Serializer\Groups({"Default", "Public"})
     */
    protected ?string $initial = null;

    /**
     * @see ProfileableInterface::setFirstName()
     */
    public function setFirstName(?string $firstName): self
    {
        $this->firstName = $firstName;

        return $this;
    }

    /**
     * @see ProfileableInterface::getFirstName()
     */
    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    /**
     * @see ProfileableInterface::setLastName()
     */
    public function setLastName(?string $lastName): self
    {
        $this->lastName = $lastName;

        return $this;
    }

    /**
     * @see ProfileableInterface::getLastName()
     */
    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    /**
     * @see ProfileableInterface::setInitial()
     */
    public function setInitial(?string $initial): self
    {
        $this->initial = $initial;

        return $this;
    }

    /**
     * @see ProfileableInterface::getInitial()
     */
    public function getInitial(): ?string
    {
        return $this->initial;
    }

    /**
     * @see ProfileableInterface::getFullName()
     *
     * @Serializer\SerializedName("full_name")
     * @Serializer\VirtualProperty
     * @Serializer\Groups({"Default", "Public"})
     */
    public function getFullName(string $format = '{firstName} {lastName}'): ?string
    {
        $fullName = null;

        if (null !== $this->firstName || null !== $this->lastName) {
            $fullName = str_replace('{firstName}', $this->firstName, $format);
            $fullName = str_replace('{lastName}', $this->lastName, $fullName);
            $fullName = trim($fullName);
        }

        return $fullName;
    }
}
