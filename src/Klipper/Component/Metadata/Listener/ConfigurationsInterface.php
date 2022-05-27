<?php

/*
 * This file is part of the Klipper package.
 *
 * (c) François Pluchino <francois.pluchino@klipper.dev>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Klipper\Component\Metadata\Listener;

/**
 * @author François Pluchino <francois.pluchino@klipper.dev>
 */
interface ConfigurationsInterface
{
    public function hasConfigurations(string $class): bool;

    /**
     * @param string $class  The attribute name for class
     * @param string $action The attribute name for action
     *
     * @throws \LogicException When multiple annotations is defined
     */
    public function getConfigurations(string $class, string $action): array;
}
