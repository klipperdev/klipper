<?php

/*
 * This file is part of the Klipper package.
 *
 * (c) François Pluchino <francois.pluchino@klipper.dev>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Klipper\Component\SecurityOauth\Bridge;

use League\OAuth2\Server\Entities\Traits\EntityTrait;
use League\OAuth2\Server\Entities\UserEntityInterface;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @author François Pluchino <francois.pluchino@klipper.dev>
 */
class User implements UserEntityInterface, UserInterface
{
    use EntityTrait;

    public function __construct($identifier)
    {
        $this->setIdentifier($identifier);
    }

    public function getRoles(): array
    {
        return [];
    }

    public function getPassword()
    {
        return null;
    }

    public function getSalt(): void
    {
        // Skip
    }

    public function eraseCredentials(): void
    {
        // Skip
    }

    public function getUsername()
    {
        return $this->getIdentifier();
    }
}
