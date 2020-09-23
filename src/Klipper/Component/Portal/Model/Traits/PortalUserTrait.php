<?php

/*
 * This file is part of the Klipper package.
 *
 * (c) François Pluchino <francois.pluchino@klipper.dev>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Klipper\Component\Portal\Model\Traits;

use Doctrine\ORM\Mapping as ORM;
use Klipper\Component\Model\Traits\EnableTrait;
use Klipper\Component\Portal\Model\PortalInterface;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * Trait for portal user model.
 *
 * @author François Pluchino <francois.pluchino@klipper.dev>
 */
trait PortalUserTrait
{
    use EnableTrait;

    /**
     * @ORM\ManyToOne(
     *     targetEntity="Klipper\Component\Portal\Model\PortalInterface",
     *     fetch="EXTRA_LAZY"
     * )
     * @ORM\JoinColumn(onDelete="CASCADE", nullable=false)
     */
    protected ?PortalInterface $portal = null;

    /**
     * @ORM\ManyToOne(
     *     targetEntity="Symfony\Component\Security\Core\User\UserInterface",
     *     fetch="EXTRA_LAZY",
     *     cascade={"persist"}
     * )
     * @ORM\JoinColumn(onDelete="CASCADE")
     */
    protected ?UserInterface $user = null;

    public function __toString(): string
    {
        return $this->portal->getName().':'.$this->user->getUsername();
    }

    public function setPortal(?PortalInterface $portal): self
    {
        $this->portal = $portal;

        return $this;
    }

    public function getPortal(): ?PortalInterface
    {
        return $this->portal;
    }

    public function setUser(?UserInterface $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getUser(): ?UserInterface
    {
        return $this->user;
    }
}
