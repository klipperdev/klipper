<?php

/*
 * This file is part of the Klipper package.
 *
 * (c) François Pluchino <francois.pluchino@klipper.dev>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Klipper\Component\Phone\Twig\Extension;

use Klipper\Component\Phone\Exception\InvalidArgumentException;
use libphonenumber\PhoneNumber;
use libphonenumber\PhoneNumberFormat;
use libphonenumber\PhoneNumberUtil;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

/**
 * @author François Pluchino <francois.pluchino@klipper.dev>
 */
class PhoneFormatExtension extends AbstractExtension
{
    protected PhoneNumberUtil $phoneNumberUtil;

    protected string $charset = 'UTF-8';

    public function __construct(PhoneNumberUtil $phoneNumberUtil)
    {
        $this->phoneNumberUtil = $phoneNumberUtil;
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('phone_format', [$this, 'format']),
        ];
    }

    /**
     * Format a phone number.
     *
     * @param PhoneNumber $phoneNumber The Phone number
     * @param int|string  $format      The name or constant of format
     *
     * @throws InvalidArgumentException If an argument is invalid
     */
    public function format(PhoneNumber $phoneNumber, string $format = PhoneNumberFormat::E164): string
    {
        if (\is_string($format)) {
            $constant = '\libphonenumber\PhoneNumberFormat::'.$format;

            if (!\defined($constant)) {
                $msg = 'The format must be either a constant value or name in libphonenumber\PhoneNumberFormat';

                throw new InvalidArgumentException($msg);
            }

            $format = \constant($constant);
        }

        return $this->phoneNumberUtil->format($phoneNumber, $format);
    }
}
