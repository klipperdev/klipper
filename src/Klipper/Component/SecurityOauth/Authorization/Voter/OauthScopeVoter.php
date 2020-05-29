<?php

/*
 * This file is part of the Klipper package.
 *
 * (c) François Pluchino <francois.pluchino@klipper.dev>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Klipper\Component\SecurityOauth\Authorization\Voter;

use Klipper\Component\Security\Authorization\Voter\AbstractIdentityVoter;

/**
 * @author François Pluchino <francois.pluchino@klipper.dev>
 */
class OauthScopeVoter extends AbstractIdentityVoter
{
    protected function getValidType(): string
    {
        return 'oauth_scope';
    }

    /**
     * {@inheritdoc}
     */
    protected function getDefaultPrefix(): string
    {
        return 'scope:';
    }
}
