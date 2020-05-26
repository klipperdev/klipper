<?php

/*
 * This file is part of the Klipper package.
 *
 * (c) François Pluchino <francois.pluchino@klipper.dev>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Klipper\Component\SecurityOauth\Model;

use Klipper\Component\Model\Traits\EnableInterface;
use Klipper\Component\Model\Traits\NameableInterface;

/**
 * @author François Pluchino <francois.pluchino@klipper.dev>
 */
interface OauthClientInterface extends NameableInterface, EnableInterface
{
    /**
     * @return static
     */
    public function setClientId(?string $secret);

    public function getClientId(): ?string;

    /**
     * @return static
     */
    public function setSecret(?string $secret);

    public function getSecret(): ?string;

    /**
     * @param string[] $redirectUri
     *
     * @return static
     */
    public function setRedirectUri(array $redirectUri);

    /**
     * @return string[]
     */
    public function getRedirectUri(): array;

    /**
     * @param string[] $grantTypes
     *
     * @return static
     */
    public function setGrantTypes(array $grantTypes);

    /**
     * @return string[]
     */
    public function getGrantTypes(): array;
}
