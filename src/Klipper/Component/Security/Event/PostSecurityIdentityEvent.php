<?php

/*
 * This file is part of the Klipper package.
 *
 * (c) François Pluchino <francois.pluchino@klipper.dev>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Klipper\Component\Security\Event;

use Klipper\Component\Security\Event\Traits\SecurityIdentityEventTrait;
use Klipper\Component\Security\Identity\SecurityIdentityInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

/**
 * The post security identity event.
 *
 * @author François Pluchino <francois.pluchino@klipper.dev>
 */
class PostSecurityIdentityEvent extends AbstractSecurityEvent
{
    use SecurityIdentityEventTrait;

    /**
     * Constructor.
     *
     * @param TokenInterface              $token              The token
     * @param SecurityIdentityInterface[] $securityIdentities The security identities
     * @param bool                        $permissionEnabled  Check if the permission manager is enabled
     */
    public function __construct(TokenInterface $token, array $securityIdentities = [], bool $permissionEnabled = true)
    {
        $this->token = $token;
        $this->securityIdentities = $securityIdentities;
        $this->permissionEnabled = $permissionEnabled;
    }
}