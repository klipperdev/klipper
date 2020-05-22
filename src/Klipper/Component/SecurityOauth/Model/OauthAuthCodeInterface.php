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

use Klipper\Component\Model\Traits\OwnerableInterface;

/**
 * @author François Pluchino <francois.pluchino@klipper.dev>
 */
interface OauthAuthCodeInterface extends OauthTokenInterface, OwnerableInterface
{
    /**
     * @return static
     */
    public function setClient(?OauthClientInterface $client);

    public function getClient(): ?OauthClientInterface;

    /**
     * @param string[] $scopes
     *
     * @return static
     */
    public function setScopes(array $scopes);

    /**
     * @return string[]
     */
    public function getScopes(): array;

    public function setRedirectUri(?string $redirectUri);

    public function getRedirectUri(): ?string;
}
