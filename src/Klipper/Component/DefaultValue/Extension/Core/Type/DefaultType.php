<?php

/*
 * This file is part of the Klipper package.
 *
 * (c) François Pluchino <francois.pluchino@klipper.dev>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Klipper\Component\DefaultValue\Extension\Core\Type;

use Klipper\Component\DefaultValue\AbstractSimpleType;

/**
 * @author François Pluchino <francois.pluchino@klipper.dev>
 */
class DefaultType extends AbstractSimpleType
{
    /**
     * Constructor.
     *
     * @param string $class The class name
     */
    public function __construct($class = 'default')
    {
        parent::__construct($class);
    }

    /**
     * {@inheritdoc}
     */
    public function getParent(): void
    {
    }
}
