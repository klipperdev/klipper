<?php

/*
 * This file is part of the Klipper package.
 *
 * (c) François Pluchino <francois.pluchino@klipper.dev>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Klipper\Component\SecurityOauth\Model\Traits;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;
use Klipper\Component\SecurityOauth\Model\OauthTokenInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @author François Pluchino <francois.pluchino@klipper.dev>
 */
trait OauthTokenTrait
{
    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     *
     * @Assert\Type(type="string")
     * @Assert\Length(max=255)
     * @Assert\NotBlank
     *
     * @Serializer\Expose
     * @Serializer\Groups(groups={"Details"})
     */
    protected ?string $token = null;

    /**
     * @ORM\Column(type="json")
     *
     * @Assert\Type(type="datetime")
     * @Assert\NotBlank
     *
     * @Serializer\Expose
     */
    protected ?\DateTimeInterface $expiresAt = null;

    /**
     * @see OauthTokenInterface::setToken()
     */
    public function setToken(?string $token): self
    {
        $this->token = $token;

        return $this;
    }

    /**
     * @see OauthTokenInterface::getToken()
     */
    public function getToken(): ?string
    {
        return $this->token;
    }

    /**
     * @see OauthTokenInterface::setExpiresAt()
     */
    public function setExpiresAt(?\DateTimeInterface $expiresAt): self
    {
        $this->expiresAt = $expiresAt;

        return $this;
    }

    /**
     * @see OauthTokenInterface::getExpiresAt()
     */
    public function getExpiresAt(): ?\DateTimeInterface
    {
        return $this->expiresAt;
    }
}
