<?php

/*
 * This file is part of the Klipper package.
 *
 * (c) François Pluchino <francois.pluchino@klipper.dev>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Klipper\Component\Portal\Security\Authentication\Provider;

use Symfony\Component\Security\Core\Authentication\Provider\AuthenticationProviderInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

/**
 * Provider for portal context.
 *
 * @author François Pluchino <francois.pluchino@klipper.dev>
 */
class PortalContextProvider implements AuthenticationProviderInterface
{
    public function authenticate(TokenInterface $token): TokenInterface
    {
        return $token;
    }

    public function supports(TokenInterface $token): bool
    {
        return false;
    }
}
