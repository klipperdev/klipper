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
use Klipper\Component\Model\Traits\UserableEditableTrait;
use Klipper\Component\Security\Model\UserInterface;
use Klipper\Component\User\Model\ProfileInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Profile trait.
 *
 * @author François Pluchino <francois.pluchino@klipper.dev>
 */
trait ProfileTrait
{
    use UserableEditableTrait;

    /**
     * @ORM\OneToOne(
     *     targetEntity="Klipper\Component\Security\Model\UserInterface",
     *     inversedBy="profile",
     *     fetch="EXTRA_LAZY",
     *     cascade={"persist", "remove"}
     * )
     * @ORM\JoinColumn(onDelete="CASCADE", nullable=false)
     */
    protected ?UserInterface $user = null;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     *
     * @Assert\Length(max=255)
     *
     * @Serializer\Expose
     */
    protected ?string $firstName = null;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     *
     * @Assert\Length(max=255)
     *
     * @Serializer\Expose
     */
    protected ?string $lastName = null;

    /**
     * @see ProfileInterface::getUsername()
     */
    public function getUsername(): ?string
    {
        return $this->getUser()
            ? $this->getUser()->getUsername()
            : null;
    }

    /**
     * @see ProfileInterface::setFirstName()
     */
    public function setFirstName(?string $firstName): self
    {
        $this->firstName = $firstName;

        return $this;
    }

    /**
     * @see ProfileInterface::getFirstName()
     */
    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    /**
     * @see ProfileInterface::setLastName()
     */
    public function setLastName(?string $lastName): self
    {
        $this->lastName = $lastName;

        return $this;
    }

    /**
     * @see ProfileInterface::getLastName()
     */
    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    /**
     * @see ProfileInterface::getFullName()
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

    /**
     * @see ProfileInterface::getInitial()
     */
    public function getInitial(): string
    {
        $initial = null;
        $fn = $this->firstName;
        $ln = $this->lastName;

        if (null !== $fn || null !== $ln) {
            $initial = null !== $fn && \strlen($fn) > 0 ? substr($fn, 0, 1) : '';
            $initial .= null !== $ln && \strlen($ln) > 0 ? substr($ln, 0, 1) : '';
        }

        if (null === $initial) {
            $initial = substr($this->getUser()->getUsername(), 0, 1);
        }

        return strtoupper($initial);
    }
}
