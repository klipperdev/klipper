<?php

/*
 * This file is part of the Klipper package.
 *
 * (c) François Pluchino <francois.pluchino@klipper.dev>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Klipper\Component\Config\Tests\Fixtures\Controller;

use Klipper\Component\Config\Tests\Fixtures\Annotation\MockAnnotation;

/**
 * @author François Pluchino <francois.pluchino@klipper.dev>
 */
class MockCallableController
{
    /**
     * @MockAnnotation
     */
    public function __invoke(): void
    {
    }
}
