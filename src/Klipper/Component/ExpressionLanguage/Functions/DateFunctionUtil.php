<?php

/*
 * This file is part of the Klipper package.
 *
 * (c) François Pluchino <francois.pluchino@klipper.dev>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Klipper\Component\ExpressionLanguage\Functions;

/**
 * Date function util.
 *
 * @author François Pluchino <francois.pluchino@klipper.dev>
 */
abstract class DateFunctionUtil
{
    public static ?string $DEFAULT_PATTERN = null;

    /**
     * Create and format the datetime from a string.
     *
     * @param string                  $datetime  The datetime in string
     * @param null|\IntlDateFormatter $formatter The date formatter
     *
     * @return bool|string
     */
    public static function create(string $datetime, \IntlDateFormatter $formatter = null)
    {
        $formatter = self::getFormatter($formatter);
        $value = self::createDateTime($datetime, $formatter);

        return $formatter->format($value);
    }

    /**
     * Create the datetime from a string.
     *
     * @param string                  $datetime  The datetime in string
     * @param null|\IntlDateFormatter $formatter The date formatter
     *
     * @return bool|\DateTime
     */
    public static function createDateTime(string $datetime, \IntlDateFormatter $formatter = null)
    {
        $value = date_create($datetime);
        $formatter = self::getFormatter($formatter);

        if (false === $value) {
            $value = self::getLocaleDateTime($datetime, $formatter);
        }

        if (false === $value) {
            $value = self::getLocaleDateTime($datetime, $formatter, false);
        }

        return $value;
    }

    /**
     * Get the date formatter.
     *
     * @param null|\IntlDateFormatter $formatter The date formatter
     */
    public static function getFormatter(?\IntlDateFormatter $formatter = null): \IntlDateFormatter
    {
        if (null === $formatter) {
            $formatter = new \IntlDateFormatter(
                \Locale::getDefault(),
                \IntlDateFormatter::SHORT,
                \IntlDateFormatter::MEDIUM,
                date_default_timezone_get(),
                \IntlDateFormatter::GREGORIAN,
                static::$DEFAULT_PATTERN
            );
        }

        return $formatter;
    }

    /**
     * Create the datetime with the localized string.
     *
     * @param string             $datetime  The datetime in string
     * @param \IntlDateFormatter $formatter The date formatter
     * @param bool               $withHours Check if the date is formatted with hours
     *
     * @return \DateTime|false
     */
    private static function getLocaleDateTime(string $datetime, \IntlDateFormatter $formatter, bool $withHours = true)
    {
        if (!$withHours) {
            $formatter = new \IntlDateFormatter(
                $formatter->getLocale(),
                $formatter->getDateType(),
                \IntlDateFormatter::NONE,
                $formatter->getTimeZoneId(),
                $formatter->getCalendar(),
                static::$DEFAULT_PATTERN
            );
        }

        $pattern = $formatter->getPattern();
        $pattern = str_replace('dd', 'd', $pattern);
        $pattern = str_replace('MM', 'm', $pattern);
        $pattern = str_replace('y', 'yy', $pattern);
        $pattern = str_replace('HH', 'H', $pattern);
        $pattern = str_replace('mm', 'i', $pattern);
        $pattern = str_replace('ss', 's', $pattern);

        $value = date_create_from_format($pattern, $datetime);

        if (!$withHours && $value instanceof \DateTime) {
            $value->setTime(0, 0);
        }

        return $value;
    }
}
