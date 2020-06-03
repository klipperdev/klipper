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
class Foobar extends Foo
{
    private ?string $customField = null;

    public function setCustomField(?string $value): void
    {
        $this->customField = $value;
    }

    public function getCustomField(): ?string
    {
        return $this->customField;
    }
}
