<?php

/*
 * This file is part of the Klipper package.
 *
 * (c) François Pluchino <francois.pluchino@klipper.dev>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Klipper\Component\Config\Tests\Fixtures\Annotation;

use Klipper\Component\Config\Annotation\AbstractAnnotation;

/**
 * @author François Pluchino <francois.pluchino@klipper.dev>
 * @Annotation
 */
class MockAnnotation extends AbstractAnnotation
{
    /**
     * @var null|string
     */
    protected $foo;

    public function setFoo(?string $foo): self
    {
        $this->foo = $foo;

        return $this;
    }

    public function getFoo(): ?string
    {
        return $this->foo;
    }

    public function getAliasName(): ?string
    {
        return 'mock';
    }
}
