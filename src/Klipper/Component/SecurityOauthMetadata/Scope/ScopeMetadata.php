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

use Klipper\Component\Metadata\ObjectMetadataBuilderInterface;
use Klipper\Component\Metadata\ObjectMetadataInterface;

/**
 * @author François Pluchino <francois.pluchino@klipper.dev>
 */
class ScopeMetadata
{
    /**
     * @param ObjectMetadataBuilderInterface|ObjectMetadataInterface|string $object
     */
    public static function getRead($object): string
    {
        return sprintf('meta/%s.readonly', self::getObject($object));
    }

    /**
     * @param ObjectMetadataBuilderInterface|ObjectMetadataInterface|string $object
     */
    public static function getManage($object): string
    {
        return sprintf('meta/%s', self::getObject($object));
    }

    /**
     * @param ObjectMetadataBuilderInterface|ObjectMetadataInterface|string $object
     */
    private static function getObject($object): string
    {
        if ($object instanceof ObjectMetadataInterface
                || $object instanceof ObjectMetadataBuilderInterface) {
            return (string) $object->getName();
        }

        return (string) $object;
    }
}
