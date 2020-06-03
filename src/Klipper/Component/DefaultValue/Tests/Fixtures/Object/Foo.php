<?php

/*
 * This file is part of the Klipper package.
 *
 * (c) François Pluchino <francois.pluchino@klipper.dev>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Klipper\Component\DefaultValue\Tests\Fixtures\Object;

/**
 * Foo class test.
 *
 * @author François Pluchino <francois.pluchino@klipper.dev>
 */
class Foo
{
    private ?string $bar = null;

    private bool $privateProperty = false;

    public function setBar(?string $value): void
    {
        $this->bar = $value;
    }

    public function getBar(): ?string
    {
        return $this->bar;
    }

    public function getPrivateProperty(): bool
    {
        return $this->privateProperty;
    }
}
