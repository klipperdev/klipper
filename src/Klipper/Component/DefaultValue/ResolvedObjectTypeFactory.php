<?php

/*
 * This file is part of the Klipper package.
 *
 * (c) François Pluchino <francois.pluchino@klipper.dev>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Klipper\Component\DefaultValue;

/**
 * @author François Pluchino <francois.pluchino@klipper.dev>
 */
class ResolvedObjectTypeFactory implements ResolvedObjectTypeFactoryInterface
{
    /**
     * {@inheritdoc}
     */
    public function createResolvedType(ObjectTypeInterface $type, array $typeExtensions, ResolvedObjectTypeInterface $parent = null)
    {
        return new ResolvedObjectType($type, $typeExtensions, $parent);
    }
}
