<?php

/*
 * This file is part of the Klipper package.
 *
 * (c) François Pluchino <francois.pluchino@klipper.dev>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Klipper\Component\Uuid\Tests\Util;

use Klipper\Component\Uuid\Util\UuidUtil;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;

/**
 * Uuid util tests.
 *
 * @author François Pluchino <francois.pluchino@klipper.dev>
 *
 * @group klipper
 * @group klipper-uuid
 *
 * @internal
 */
final class UuidUtilTest extends TestCase
{
    /**
     * @throws \Exception
     */
    public function getUuidStrings(): array
    {
        return [
            ['foo.bar', false],
            [Uuid::uuid4()->toString(), true],
        ];
    }

    /**
     * @dataProvider getUuidStrings
     *
     * @param string $string the string
     * @param bool   $valid  Check if the string is a uuid v4
     */
    public function testIsV4($string, $valid): void
    {
        static::assertSame($valid, UuidUtil::isV4($string));
    }
}
