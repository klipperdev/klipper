<?php

/*
 * This file is part of the Klipper package.
 *
 * (c) François Pluchino <francois.pluchino@klipper.dev>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Klipper\Component\SecurityOauth\Scope;

use Klipper\Component\SecurityOauth\Annotation\OauthScope;

/**
 * @author François Pluchino <francois.pluchino@klipper.dev>
 */
class ScopeVote extends OauthScope
{
    /**
     * @param string|string[] $scope
     */
    public function __construct($scope, bool $allRequired = false)
    {
        parent::__construct([
            'scope' => (array) $scope,
            'allRequired' => $allRequired,
        ]);
    }
}
