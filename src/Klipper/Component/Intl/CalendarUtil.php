<?php

/*
 * This file is part of the Klipper package.
 *
 * (c) François Pluchino <francois.pluchino@klipper.dev>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Klipper\Component\Intl;

/**
 * @author François Pluchino <francois.pluchino@klipper.dev>
 */
abstract class CalendarUtil
{
    /**
     * Get the name of day of week.
     *
     * @param int $day The number of day of week
     */
    public static function getWeekDay(int $day): string
    {
        $days = static::getWeekDays();
        $day = max(0, $day);
        $day = min(6, $day);

        return $days[(int) $day];
    }

    /**
     * Get the week of days.
     *
     * @return string[]
     */
    public static function getWeekDays(): array
    {
        return [
            \IntlCalendar::DOW_SUNDAY - 1 => 'Sunday',
            \IntlCalendar::DOW_MONDAY - 1 => 'Monday',
            \IntlCalendar::DOW_TUESDAY - 1 => 'Tuesday',
            \IntlCalendar::DOW_WEDNESDAY - 1 => 'Wednesday',
            \IntlCalendar::DOW_THURSDAY - 1 => 'Thursday',
            \IntlCalendar::DOW_FRIDAY - 1 => 'Friday',
            \IntlCalendar::DOW_SATURDAY - 1 => 'Saturday',
        ];
    }

    /**
     * Get the localized week day.
     *
     * @param int         $day      The number of day of week
     * @param null|string $locale   The locale of IntlDateFormatter
     * @param null|string $timezone The timezone of IntlDateFormatter
     *
     * @throws
     */
    public static function getLocalizedWeekDay(int $day, ?string $locale = null, ?string $timezone = null): string
    {
        $formatter = static::createFormatter($locale, $timezone);
        $dayName = static::getWeekDay($day);
        $dayDate = new \DateTime();
        $dayDate->setTimestamp(static::generateNextTimestamp($dayName));

        return ucfirst($formatter->format($dayDate));
    }

    /**
     * Get the localized week days, ordered by the week day defined by the default locale and timezone.
     *
     * The index is the number of the week day.
     *
     * @param null|string $locale   The locale of IntlDateFormatter
     * @param null|string $timezone The timezone of IntlDateFormatter
     *
     * @return array The day number and the localized day name
     *
     * @throws
     */
    public static function getLocalizedWeekDays(?string $locale = null, ?string $timezone = null): array
    {
        $formatter = static::createFormatter($locale, $timezone);
        $calendar = static::createCalendar($locale, $timezone);
        $firstDayName = static::getWeekDay($calendar->getFirstDayOfWeek() - 1);
        $day = new \DateTime();
        $day->setTimestamp(static::generateNextTimestamp($firstDayName));
        $days = [];

        for ($i = 0; $i < 7; ++$i) {
            $day = $day->add(\DateInterval::createFromDateString((0 === $i ? 0 : 1).' day'));
            $days[date('w', $day->getTimestamp())] = ucfirst($formatter->format($day));
        }

        return $days;
    }

    /**
     * Get the first day of week, defined by the default locale and timezone.
     *
     * The value is is the index number of the week day.
     *
     * @param null|string $locale   The locale of IntlDateFormatter
     * @param null|string $timezone The timezone of IntlDateFormatter
     */
    public static function getFirstDayOfWeek(?string $locale = null, ?string $timezone = null): int
    {
        $calendar = static::createCalendar($locale, $timezone);

        return $calendar->getFirstDayOfWeek() - 1;
    }

    /**
     * Get the name for the first day of week, defined by the default locale and timezone.
     *
     * The value is is the name of the week day.
     *
     * @param null|string $locale   The locale of IntlDateFormatter
     * @param null|string $timezone The timezone of IntlDateFormatter
     */
    public static function getFirstDayOfWeekName(?string $locale = null, ?string $timezone = null): string
    {
        return static::getWeekDay(static::getFirstDayOfWeek($locale, $timezone));
    }

    /**
     * Get the last day of week, defined by the default locale and timezone.
     *
     * The value is is the index number of the week day.
     *
     * @param null|string $locale   The locale of IntlDateFormatter
     * @param null|string $timezone The timezone of IntlDateFormatter
     *
     * @throws
     */
    public static function getLastDayOfWeek(?string $locale = null, ?string $timezone = null): int
    {
        $firstDay = static::getFirstDayOfWeekName($locale, $timezone);
        $day = new \DateTime();
        $day->setTimestamp(static::generateNextTimestamp($firstDay));
        $day->add(\DateInterval::createFromDateString('+6 days'));

        return date('w', $day->getTimestamp());
    }

    /**
     * Get the name for the last day of week, defined by the default locale and timezone.
     *
     * The value is is the name of the week day.
     *
     * @param null|string $locale   The locale of IntlDateFormatter
     * @param null|string $timezone The timezone of IntlDateFormatter
     */
    public static function getLastDayOfWeekName(?string $locale = null, ?string $timezone = null): string
    {
        return static::getWeekDay(static::getLastDayOfWeek($locale, $timezone));
    }

    /**
     * Create the intl date formatter.
     *
     * @param null|string $locale   The locale of IntlDateFormatter
     * @param null|string $timezone The timezone of IntlDateFormatter
     */
    protected static function createFormatter(?string $locale = null, ?string $timezone = null): \IntlDateFormatter
    {
        $locale = $locale ?? \Locale::getDefault();
        $timezone = $timezone ?? date_default_timezone_get();
        $formatter = \IntlDateFormatter::create(
            $locale,
            \IntlDateFormatter::FULL,
            \IntlDateFormatter::FULL,
            $timezone
        );
        $formatter->setPattern('eeee');

        return $formatter;
    }

    /**
     * Create the intl calendar.
     *
     * @param null|string $locale   The locale of IntlDateFormatter
     * @param null|string $timezone The timezone of IntlDateFormatter
     */
    protected static function createCalendar(?string $locale = null, ?string $timezone = null): \IntlCalendar
    {
        $intlTimezone = null !== $timezone
            ? \IntlTimeZone::fromDateTimeZone(new \DateTimeZone($timezone))
            : null;

        return \IntlCalendar::createInstance($intlTimezone, $locale);
    }

    /**
     * Generate the next timestamp.
     *
     * @param string $value The time value
     */
    protected static function generateNextTimestamp(string $value): int
    {
        return strtotime('next '.$value, time());
    }
}
