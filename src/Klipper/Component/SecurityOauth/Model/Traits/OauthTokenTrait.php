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
use Klipper\Component\Model\Traits\ExpirableTrait;
use Klipper\Component\SecurityOauth\Model\OauthTokenInterface;

/**
 * @author François Pluchino <francois.pluchino@klipper.dev>
 */
trait OauthTokenTrait
{
    use ExpirableTrait;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     *
     * @\Symfony\Component\Validator\Constraints\Type(type="string")
     * @\Symfony\Component\Validator\Constraints\Length(max=255)
     * @\Symfony\Component\Validator\Constraints\NotBlank
     *
     * @Serializer\Expose
     * @Serializer\Groups(groups={"Details"})
     */
    protected ?string $token = null;

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
}
