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
use Klipper\Component\Security\Model\UserInterface;
use Klipper\Component\User\Model\ProfileInterface;
use Klipper\Component\Uuid\Util\UuidUtil;

/**
 * Profileable trait.
 *
 * @author François Pluchino <francois.pluchino@klipper.dev>
 */
trait ProfileableTrait
{
    /**
     * @ORM\OneToOne(
     *     targetEntity="Klipper\Component\User\Model\ProfileInterface",
     *     mappedBy="user",
     *     fetch="EAGER",
     *     orphanRemoval=true,
     *     cascade={"persist", "remove"}
     * )
     */
    protected ?ProfileInterface $profile = null;

    /**
     * @see ProfileableInterface::setProfile()
     */
    public function setProfile(?ProfileInterface $profile): self
    {
        $this->profile = $profile;

        if ($this instanceof UserInterface) {
            $profile->setUser($this);
        }

        return $this;
    }

    /**
     * @see ProfileableInterface::getProfile()
     */
    public function getProfile(): ?ProfileInterface
    {
        return $this->profile;
    }

    /**
     * @see ProfileableInterface::getFullName()
     */
    public function getFullName(string $format = '{firstName} {lastName}'): string
    {
        $fullName = null !== $this->profile
            ? $this->profile->getFullName($format)
            : $this->getUsername();

        return UuidUtil::isV4($fullName)
            ? $this->getEmail()
            : $fullName;
    }
}
