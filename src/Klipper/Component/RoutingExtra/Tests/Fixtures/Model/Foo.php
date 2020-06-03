<?php

/*
 * This file is part of the Klipper package.
 *
 * (c) François Pluchino <francois.pluchino@klipper.dev>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Klipper\Component\RoutingExtra\Tests\Fixtures\Model;

/**
 * @author François Pluchino <francois.pluchino@klipper.dev>
 */
class Foo
{
    private ?int $bar;

    /**
     * @param int $bar
     */
    public function setBar(?int $bar): void
    {
        $this->bar = $bar;
    }

    /**
     * @return int
     */
    public function getBar(): ?int
    {
        return $this->bar;
    }
}
