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
use Gedmo\Mapping\Annotation as Gedmo;
use JMS\Serializer\Annotation as Serializer;
use Klipper\Component\Security\Model\UserInterface;

/**
 * Trait of user trackable model.
 *
 * @author François Pluchino <francois.pluchino@klipper.dev>
 */
trait UserTrackableTrait
{
    /**
     * @ORM\ManyToOne(
     *     targetEntity="Klipper\Component\Security\Model\UserInterface"
     * )
     * @ORM\JoinColumn(onDelete="SET NULL", nullable=true)
     * @Gedmo\Blameable(on="create")
     * @Serializer\Expose
     * @Serializer\MaxDepth(1)
     * @Serializer\ReadOnlyProperty
     */
    protected ?UserInterface $createdBy = null;

    /**
     * @ORM\ManyToOne(
     *     targetEntity="Klipper\Component\Security\Model\UserInterface"
     * )
     * @ORM\JoinColumn(onDelete="SET NULL", nullable=true)
     * @Gedmo\Blameable(on="update")
     * @Serializer\Expose
     * @Serializer\MaxDepth(1)
     * @Serializer\ReadOnlyProperty
     */
    protected ?UserInterface $updatedBy = null;

    /**
     * @return static
     */
    public function setCreatedBy(?UserInterface $createdBy): self
    {
        $this->createdBy = $createdBy;

        return $this;
    }

    /**
     * @see \Klipper\Contracts\Model\UserTrackableInterface::getCreatedBy()
     */
    public function getCreatedBy(): ?UserInterface
    {
        return $this->createdBy;
    }

    /**
     * @see \Klipper\Contracts\Model\UserTrackableInterface::getCreatedById()
     */
    public function getCreatedById()
    {
        return null !== $this->getCreatedBy()
            ? $this->getCreatedBy()->getId()
            : null;
    }

    /**
     * @return static
     */
    public function setUpdatedBy(?UserInterface $updatedBy): self
    {
        $this->updatedBy = $updatedBy;

        return $this;
    }

    /**
     * @see \Klipper\Contracts\Model\UserTrackableInterface::getUpdatedBy()
     */
    public function getUpdatedBy(): ?UserInterface
    {
        return $this->updatedBy;
    }

    /**
     * @see \Klipper\Contracts\Model\UserTrackableInterface::getUpdatedById()
     */
    public function getUpdatedById()
    {
        return null !== $this->getUpdatedBy()
            ? $this->getUpdatedBy()->getId()
            : null;
    }
}
