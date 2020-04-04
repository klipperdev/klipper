<?php

/*
 * This file is part of the Klipper package.
 *
 * (c) François Pluchino <francois.pluchino@klipper.dev>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Klipper\Component\Metadata;

/**
 * @author François Pluchino <francois.pluchino@klipper.dev>
 */
abstract class MetadataContexts
{
    /**
     * @var string
     */
    public const USER = 'user';

    /**
     * @var string
     */
    public const ORGANIZATION = 'organization';
}
