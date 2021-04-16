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

use Klipper\Component\Portal\Model\Traits\PortalFeatureableInterface;
use Klipper\Component\Portal\PortalContextInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

/**
 * PortalFeatureVoter to determine the portal feature granted on current user defined in token.
 *
 * @author François Pluchino <francois.pluchino@klipper.dev>
 */
class PortalFeatureVoter extends Voter
{
    private PortalContextInterface $portalContext;

    public function __construct(PortalContextInterface $portalContext)
    {
        $this->portalContext = $portalContext;
    }

    protected function supports($attribute, $subject): bool
    {
        return \is_string($attribute) && 'portal:feature' === $attribute;
    }

    protected function voteOnAttribute($attribute, $subject, TokenInterface $token): bool
    {
        $portal = $this->portalContext->getCurrentPortal();

        if (null === $portal || !$portal instanceof PortalFeatureableInterface || !\is_string($subject)) {
            return false;
        }

        return \in_array($subject, $portal->getPortalFeatureValues(), true);
    }
}
