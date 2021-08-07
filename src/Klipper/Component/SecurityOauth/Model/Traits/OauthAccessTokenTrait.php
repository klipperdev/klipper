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
use Klipper\Component\SecurityOauth\Model\OauthAccessTokenInterface;
use Klipper\Component\SecurityOauth\Model\OauthClientInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @author François Pluchino <francois.pluchino@klipper.dev>
 */
trait OauthAccessTokenTrait
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
     * @Serializer\ReadOnlyProperty
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
     * @see OauthAccessTokenInterface::setClient()
     */
    public function setClient(?OauthClientInterface $client): self
    {
        $this->client = $client;

        return $this;
    }

    /**
     * @see OauthAccessTokenInterface::getClient()
     */
    public function getClient(): ?OauthClientInterface
    {
        return $this->client;
    }

    /**
     * @see OauthAccessTokenInterface::setScopes()
     */
    public function setScopes(array $scopes): self
    {
        $this->scopes = $scopes;

        return $this;
    }

    /**
     * @see OauthAccessTokenInterface::getScopes()
     */
    public function getScopes(): array
    {
        return $this->scopes;
    }
}
