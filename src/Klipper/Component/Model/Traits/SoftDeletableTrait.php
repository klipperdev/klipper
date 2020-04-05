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
use Klipper\Component\Resource\Model\SoftDeletableInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Trait of soft deletable model.
 *
 * @author François Pluchino <francois.pluchino@klipper.dev>
 */
trait SoftDeletableTrait
{
    /**
     * @var null|\DateTime
     *
     * @ORM\Column(type="datetime", nullable=true)
     *
     * @Assert\Type(type="datetime")
     */
    protected $deletedAt;

    /**
     * @see SoftDeletableInterface::setDeletedAt()
     *
     * @return static
     */
    public function setDeletedAt(?\DateTime $deletedAt = null): self
    {
        $this->deletedAt = $deletedAt;

        return $this;
    }

    /**
     * @see SoftDeletableInterface::getDeletedAt()
     */
    public function getDeletedAt(): ?\DateTime
    {
        return $this->deletedAt;
    }

    /**
     * @see SoftDeletableInterface::isDeleted()
     */
    public function isDeleted(): bool
    {
        return null !== $this->deletedAt;
    }
}
