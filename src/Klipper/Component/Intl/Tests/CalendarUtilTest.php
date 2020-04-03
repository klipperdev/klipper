<?php

/*
 * This file is part of the Klipper package.
 *
 * (c) François Pluchino <francois.pluchino@klipper.dev>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Klipper\Component\Intl\Tests;

use Klipper\Component\Intl\CalendarUtil;
use PHPUnit\Framework\TestCase;

/**
 * Calendar util tests.
 *
 * @author François Pluchino <francois.pluchino@klipper.dev>
 *
 * @group klipper
 * @group klipper-intl
 *
 * @internal
 */
final class CalendarUtilTest extends TestCase
{
    public function testGetWeekDay(): void
    {
        static::assertSame('Sunday', CalendarUtil::getWeekDay(0));
        static::assertSame('Monday', CalendarUtil::getWeekDay(1));
        static::assertSame('Tuesday', CalendarUtil::getWeekDay(2));
        static::assertSame('Wednesday', CalendarUtil::getWeekDay(3));
        static::assertSame('Thursday', CalendarUtil::getWeekDay(4));
        static::assertSame('Friday', CalendarUtil::getWeekDay(5));
        static::assertSame('Saturday', CalendarUtil::getWeekDay(6));

        static::assertSame('Sunday', CalendarUtil::getWeekDay(-1));
        static::assertSame('Saturday', CalendarUtil::getWeekDay(7));
    }

    public function testGetWeekDays(): void
    {
        $valid = [
            0 => 'Sunday',
            1 => 'Monday',
            2 => 'Tuesday',
            3 => 'Wednesday',
            4 => 'Thursday',
            5 => 'Friday',
            6 => 'Saturday',
        ];

        static::assertSame($valid, CalendarUtil::getWeekDays());
    }

    public function getLocalizedDays(): array
    {
        return [
            [0, 'Sunday', 'Dimanche'],
            [1, 'Monday', 'Lundi'],
            [2, 'Tuesday', 'Mardi'],
            [3, 'Wednesday', 'Mercredi'],
            [4, 'Thursday', 'Jeudi'],
            [5, 'Friday', 'Vendredi'],
            [6, 'Saturday', 'Samedi'],
        ];
    }

    /**
     * @dataProvider getLocalizedDays
     *
     * @param int    $day
     * @param string $validEnglish
     * @param string $validFrench
     */
    public function testGetLocalizedWeekDay($day, $validEnglish, $validFrench): void
    {
        static::assertSame($validEnglish, CalendarUtil::getLocalizedWeekDay($day, 'en_US'));
        static::assertSame($validFrench, CalendarUtil::getLocalizedWeekDay($day, 'fr_CA'));
        static::assertSame($validFrench, CalendarUtil::getLocalizedWeekDay($day, 'fr_FR'));
    }

    public function getWeekDaysLocales(): array
    {
        return [
            ['en_US', [
                0 => 'Sunday',
                1 => 'Monday',
                2 => 'Tuesday',
                3 => 'Wednesday',
                4 => 'Thursday',
                5 => 'Friday',
                6 => 'Saturday',
            ]],
            ['fr_CA', [
                0 => 'Dimanche',
                1 => 'Lundi',
                2 => 'Mardi',
                3 => 'Mercredi',
                4 => 'Jeudi',
                5 => 'Vendredi',
                6 => 'Samedi',
            ]],
            ['fr_FR', [
                1 => 'Lundi',
                2 => 'Mardi',
                3 => 'Mercredi',
                4 => 'Jeudi',
                5 => 'Vendredi',
                6 => 'Samedi',
                0 => 'Dimanche',
            ]],
        ];
    }

    /**
     * @dataProvider getWeekDaysLocales
     *
     * @param string $locale
     */
    public function testGetLocalizedWeekDays($locale, array $validDays): void
    {
        static::assertSame($validDays, CalendarUtil::getLocalizedWeekDays($locale));
    }
}
