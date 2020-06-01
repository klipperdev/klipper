<?php

/*
 * This file is part of the Klipper package.
 *
 * (c) François Pluchino <francois.pluchino@klipper.dev>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Klipper\Component\SecurityOauthMetadata\Scope;

/**
 * @author François Pluchino <francois.pluchino@klipper.dev>
 */
class ScopeTypes
{
    public const READ_ACTIONS = [
        'list',
        'view',
    ];

    public const MANAGE_ACTIONS = [
        'create',
        'upsert',
        'update',
        'delete',
        'undelete',
        'creates',
        'upserts',
        'updates',
        'deletes',
        'undeletes',
    ];
}
