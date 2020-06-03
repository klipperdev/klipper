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
interface ObjectBuilderInterface extends ObjectConfigBuilderInterface
{
    /**
     * Returns the associated block factory.
     *
     * @return ObjectFactoryInterface The factory
     */
    public function getObjectFactory(): ObjectFactoryInterface;

    /**
     * Creates the block.
     *
     * @return object The object instance
     */
    public function getObject(): object;
}
