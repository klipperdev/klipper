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
use Klipper\Component\SecurityOauth\Model\OauthClientInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @author François Pluchino <francois.pluchino@klipper.dev>
 */
trait OauthClientTrait
{
    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     *
     * @Assert\Type(type="string")
     * @Assert\Length(max=255)
     *
     * @Serializer\Expose
     */
    protected ?string $clientId = null;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     *
     * @Assert\Type(type="string")
     * @Assert\Length(max=255)
     *
     * @Serializer\Expose
     * @Serializer\Groups(groups={"Details"})
     */
    protected ?string $secret = null;

    /**
     * @ORM\Column(type="json")
     *
     * @Assert\All({
     *     @Assert\Url,
     *     @Assert\Length(max=255),
     *     @Assert\NotBlank
     * })
     *
     * @Serializer\Expose
     */
    protected array $redirectUri = [];

    /**
     * @ORM\Column(type="json")
     *
     * @Assert\Choice(
     *     multiple=true,
     *     min=1,
     *     choices={
     *         "client_credentials",
     *         "code",
     *         "implicit",
     *         "password",
     *         "refresh_token"
     *     }
     * )
     *
     * @Serializer\Expose
     */
    protected array $grantTypes = [];

    /**
     * @see OauthClientInterface::setClientId()
     */
    public function setClientId(?string $clientId): self
    {
        $this->clientId = $clientId;

        return $this;
    }

    /**
     * @see OauthClientInterface::getClientId()
     */
    public function getClientId(): ?string
    {
        return $this->clientId;
    }

    /**
     * @see OauthClientInterface::setSecret()
     */
    public function setSecret(?string $secret): self
    {
        $this->secret = $secret;

        return $this;
    }

    /**
     * @see OauthClientInterface::getSecret()
     */
    public function getSecret(): ?string
    {
        return $this->secret;
    }

    /**
     * @see OauthClientInterface::setRedirectUri()
     */
    public function setRedirectUri(array $redirectUri): self
    {
        $this->redirectUri = $redirectUri;

        return $this;
    }

    /**
     * @see OauthClientInterface::getRedirectUri()
     */
    public function getRedirectUri(): array
    {
        return $this->redirectUri;
    }

    /**
     * @see OauthClientInterface::setGrantTypes()
     */
    public function setGrantTypes(array $grantTypes): self
    {
        $this->grantTypes = $grantTypes;

        return $this;
    }

    /**
     * @see OauthClientInterface::getGrantTypes()
     */
    public function getGrantTypes(): array
    {
        return $this->grantTypes;
    }
}
