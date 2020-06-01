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
use Klipper\Component\SecurityOauth\Model\OauthRefreshTokenInterface;

/**
 * @author François Pluchino <francois.pluchino@klipper.dev>
 */
trait OauthRefreshTokenTrait
{
    use OauthTokenTrait;

    /**
     * @ORM\ManyToOne(
     *     targetEntity="Klipper\Component\SecurityOauth\Model\OauthAccessTokenInterface",
     *     fetch="EXTRA_LAZY"
     * )
     * @ORM\JoinColumn(onDelete="SET NULL")
     *
     * @Serializer\Expose
     * @Serializer\ReadOnly
     */
    protected ?OauthAccessTokenInterface $accessToken;

    /**
     * @see OauthRefreshTokenInterface::setAccessToken()
     */
    public function setAccessToken(?OauthAccessTokenInterface $accessToken): self
    {
        $this->accessToken = $accessToken;

        return $this;
    }

    /**
     * @see OauthRefreshTokenInterface::getAccessToken()
     */
    public function getAccessToken(): ?OauthAccessTokenInterface
    {
        return $this->accessToken;
    }
}
