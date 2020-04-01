<?php

/*
 * This file is part of the Klipper package.
 *
 * (c) François Pluchino <francois.pluchino@klipper.dev>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Klipper\Component\Mailer\Tests\Twig\Loader;

use Klipper\Component\Mailer\Mime\SandboxTemplaterInterface;
use Klipper\Component\Mailer\Twig\Loader\SandboxLoader;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Twig\Error\LoaderError;
use Twig\Source;

/**
 * @author François Pluchino <francois.pluchino@klipper.dev>
 *
 * @internal
 */
final class SandboxLoaderTest extends TestCase
{
    /**
     * @var MockObject|SandboxTemplaterInterface
     */
    protected $sandboxTemplater;

    /**
     * @var SandboxLoader
     */
    protected $loader;

    protected function setUp(): void
    {
        $this->sandboxTemplater = $this->getMockBuilder(SandboxTemplaterInterface::class)->getMock();
        $this->loader = new SandboxLoader($this->sandboxTemplater, ['test_namespace']);
    }

    protected function tearDown(): void
    {
        $this->sandboxTemplater = null;
        $this->loader = null;
    }

    /**
     * @throws
     */
    public function testGetSourceContext(): void
    {
        static::assertEquals(new Source('', 'name'), $this->loader->getSourceContext('name'));
    }

    /**
     * @throws
     */
    public function testGetCacheKey(): void
    {
        static::assertSame('name', $this->loader->getCacheKey('name'));
    }

    /**
     * @throws
     */
    public function testIsFresh(): void
    {
        static::assertFalse($this->loader->isFresh('name', 0));
    }

    /**
     * @throws
     */
    public function testExistsWithoutSandbox(): void
    {
        $this->sandboxTemplater->expects(static::once())
            ->method('isSandboxed')
            ->willReturn(false)
        ;

        static::assertFalse($this->loader->exists('name'));
    }

    /**
     * @throws
     */
    public function testExistsWithSandboxAndValidNamespace(): void
    {
        $this->sandboxTemplater->expects(static::once())
            ->method('isSandboxed')
            ->willReturn(true)
        ;

        static::assertFalse($this->loader->exists('@test_namespace/name'));
    }

    /**
     * @throws
     */
    public function testExistsWithSandboxAndInvalidNamespace(): void
    {
        $this->expectException(LoaderError::class);
        $this->expectExceptionMessage('Unable to find template "@invalid_namespace/name".');

        $this->sandboxTemplater->expects(static::once())
            ->method('isSandboxed')
            ->willReturn(true)
        ;

        $this->loader->exists('@invalid_namespace/name');
    }

    /**
     * @throws
     */
    public function testExistsWithSandboxAndInvalidNamespaceWithoutName(): void
    {
        $this->expectException(LoaderError::class);
        $this->expectExceptionMessage('Unable to find template "@invalid_namespace_without_name".');

        $this->sandboxTemplater->expects(static::once())
            ->method('isSandboxed')
            ->willReturn(true)
        ;

        $this->loader->exists('@invalid_namespace_without_name');
    }

    /**
     * @throws
     */
    public function testExistsWithSandboxAndWithoutNamespace(): void
    {
        $this->expectException(LoaderError::class);
        $this->expectExceptionMessage('Unable to find template "name".');

        $this->sandboxTemplater->expects(static::once())
            ->method('isSandboxed')
            ->willReturn(true)
        ;

        $this->loader->exists('name');
    }
}
