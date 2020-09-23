<?php

/*
 * This file is part of the Klipper package.
 *
 * (c) François Pluchino <francois.pluchino@klipper.dev>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Klipper\Component\Portal\Security\Authorization\Voter;

use Klipper\Component\Portal\Model\PortalInterface;
use Klipper\Component\Security\Authorization\Voter\AbstractIdentityVoter;

/**
 * PortalVoter to determine the portal granted on current user defined in token.
 *
 * @author François Pluchino <francois.pluchino@klipper.dev>
 */
class PortalVoter extends AbstractIdentityVoter
{
    protected function getValidType(): string
    {
        return PortalInterface::class;
    }

    protected function getDefaultPrefix(): string
    {
        return 'PORTAL_';
    }
}
