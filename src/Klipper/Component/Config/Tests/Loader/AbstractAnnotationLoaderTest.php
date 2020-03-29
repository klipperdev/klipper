<?php

/*
 * This file is part of the Klipper package.
 *
 * (c) François Pluchino <francois.pluchino@klipper.dev>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Klipper\Component\Config\Tests\Loader;

use Doctrine\Common\Annotations\Reader;
use Klipper\Component\Config\Loader\AbstractAnnotationLoader;
use Klipper\Component\Config\Loader\ClassFinder;
use PHPUnit\Framework\TestCase;

/**
 * @author François Pluchino <francois.pluchino@klipper.dev>
 *
 * @internal
 */
final class AbstractAnnotationLoaderTest extends TestCase
{
    /**
     * @throws
     */
    public function testConstructor(): void
    {
        /** @var Reader $reader */
        $reader = $this->getMockBuilder(Reader::class)->getMock();
        $classFinder = new ClassFinder();

        $loader = $this->getMockForAbstractClass(AbstractAnnotationLoader::class, [$reader, $classFinder]);

        static::assertInstanceOf(AbstractAnnotationLoader::class, $loader);
    }
}
