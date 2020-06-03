<?php

/*
 * This file is part of the Klipper package.
 *
 * (c) François Pluchino <francois.pluchino@klipper.dev>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Klipper\Component\Routing\Tests\Util;

use Klipper\Component\Routing\Util\HostUtil;
use PHPUnit\Framework\TestCase;

/**
 * Host util tests.
 *
 * @author François Pluchino <francois.pluchino@klipper.dev>
 *
 * @group klipper
 * @group klipper-routing
 *
 * @internal
 */
final class HostUtilTest extends TestCase
{
    public function getRoutes(): array
    {
        return [
            ['*.domain.tld/{parameter}', 'api.domain.tld', '/{parameter}', true],
            ['*.domain.tld/{parameter}', 'api.domain.tld', '/', false],
            ['*', 'api.domain.tld', '/{parameter}', true],
            ['*', 'api.domain.tld', '/', true],
            ['/^([a-z]+).domain.tld|\/\{parameter\}/', 'api.domain.tld', '/{parameter}', true],
        ];
    }

    /**
     * @dataProvider getRoutes
     *
     * @param string $pattern The pattern
     * @param string $host    The host
     * @param string $path    The path
     * @param bool   $valid   Check if the string is a uuid v4
     */
    public function testIsRouteValid(string $pattern, string $host, string $path, bool $valid): void
    {
        static::assertSame($valid, HostUtil::isRouteValid($pattern, $host, $path));
    }
}
