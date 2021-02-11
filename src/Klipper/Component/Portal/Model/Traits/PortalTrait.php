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
use JMS\Serializer\Annotation as Serializer;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Trait for portal model.
 *
 * @author François Pluchino <francois.pluchino@klipper.dev>
 */
trait PortalTrait
{
    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     *
     * @Assert\Type(type="string")
     * @Assert\Length(max=255)
     * @Assert\Expression(
     *     expression="!(!value && this.isPortalEnabled())",
     *     message="This value should not be blank."
     * )
     *
     * @Serializer\Expose
     */
    protected ?string $portalName = null;

    /**
     * @ORM\Column(type="boolean")
     *
     * @Assert\Type(type="boolean")
     *
     * @Serializer\Expose
     */
    protected bool $portalEnabled = false;

    public function __toString(): string
    {
        return (string) $this->getPortalName();
    }

    /**
     * @return static
     */
    public function setPortalName(?string $name): self
    {
        $this->portalName = $name;

        return $this;
    }

    public function getPortalName(): ?string
    {
        return $this->portalName;
    }

    /**
     * @return static
     */
    public function setPortalEnabled(bool $enabled): self
    {
        $this->portalEnabled = $enabled;

        return $this;
    }

    public function isPortalEnabled(): bool
    {
        return $this->portalEnabled;
    }
}
