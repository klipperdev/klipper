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
use Klipper\Component\SecurityOauth\Model\OauthAuthCodeInterface;
use Klipper\Component\SecurityOauth\Model\OauthClientInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @author François Pluchino <francois.pluchino@klipper.dev>
 */
trait OauthAuthCodeTrait
{
    use OauthTokenTrait;

    /**
     * @ORM\ManyToOne(
     *     targetEntity="Klipper\Component\SecurityOauth\Model\OauthClientInterface",
     *     fetch="EXTRA_LAZY"
     * )
     * @ORM\JoinColumn(onDelete="CASCADE")
     *
     * @Serializer\Type("AssociationId")
     * @Serializer\Expose
     * @Serializer\ReadOnly
     */
    protected ?OauthClientInterface $client = null;

    /**
     * @ORM\Column(type="json")
     *
     * @Assert\All({
     *     @Assert\Type(type="string"),
     *     @Assert\Length(max=255),
     *     @Assert\NotBlank
     * })
     *
     * @Serializer\Expose
     */
    protected array $scopes = [];

    /**
     * @ORM\Column(type="string", length=255)
     *
     * @Assert\Type(type="string")
     * @Assert\Length(max=255)
     * @Assert\NotBlank
     *
     * @Serializer\Expose
     * @Serializer\Groups(groups={"Details"})
     */
    protected ?string $redirectUri = null;

    /**
     * @see OauthAuthCodeInterface::setClient()
     */
    public function setClient(?OauthClientInterface $client): self
    {
        $this->client = $client;

        return $this;
    }

    /**
     * @see OauthAuthCodeInterface::getClient()
     */
    public function getClient(): ?OauthClientInterface
    {
        return $this->client;
    }

    /**
     * @see OauthAuthCodeInterface::setScopes()
     */
    public function setScopes(array $scopes): self
    {
        $this->scopes = $scopes;

        return $this;
    }

    /**
     * @see OauthAuthCodeInterface::getScopes()
     */
    public function getScopes(): array
    {
        return $this->scopes;
    }

    /**
     * @see OauthAuthCodeInterface::setRedirectUri()
     */
    public function setRedirectUri(?string $redirectUri): self
    {
        $this->redirectUri = $redirectUri;

        return $this;
    }

    /**
     * @see OauthAuthCodeInterface::getRedirectUri()
     */
    public function getRedirectUri(): ?string
    {
        return $this->redirectUri;
    }
}
