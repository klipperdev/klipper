<?php

/*
 * This file is part of the Klipper package.
 *
 * (c) François Pluchino <francois.pluchino@klipper.dev>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Klipper\Bundle\ApiSecurityOauthBundle\Scope\Loader;

use Klipper\Component\SecurityOauth\Scope\Loader\ScopeLoaderInterface;
use Symfony\Component\Config\Resource\ResourceInterface;

/**
 * @author François Pluchino <francois.pluchino@klipper.dev>
 */
interface ScopeLoaderResourcableInterface extends ScopeLoaderInterface
{
    /**
     * @return ResourceInterface[]
     */
    public function getResources(): array;
}
