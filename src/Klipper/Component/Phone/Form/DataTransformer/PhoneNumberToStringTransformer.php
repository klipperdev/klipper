<?php

/*
 * This file is part of the Klipper package.
 *
 * (c) François Pluchino <francois.pluchino@klipper.dev>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Klipper\Component\Phone\Form\DataTransformer;

use libphonenumber\NumberParseException;
use libphonenumber\PhoneNumber;
use libphonenumber\PhoneNumberFormat;
use libphonenumber\PhoneNumberUtil;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;

/**
 * Phone number to string.
 *
 * @author François Pluchino <francois.pluchino@klipper.dev>
 */
class PhoneNumberToStringTransformer implements DataTransformerInterface
{
    /**
     * @var string
     */
    private $defaultRegion;

    /**
     * @var int
     */
    private $format;

    /**
     * @param string $defaultRegion The default region code
     * @param int    $format        The display format
     */
    public function __construct(
        $defaultRegion = PhoneNumberUtil::UNKNOWN_REGION,
        $format = PhoneNumberFormat::INTERNATIONAL
    ) {
        $this->defaultRegion = $defaultRegion;
        $this->format = $format;
    }

    /**
     * @param mixed $value
     */
    public function transform($value): string
    {
        if (null === $value) {
            return '';
        }
        if (false === $value instanceof PhoneNumber) {
            throw new TransformationFailedException('Expected a \libphonenumber\PhoneNumber.');
        }

        $util = PhoneNumberUtil::getInstance();

        if (PhoneNumberFormat::NATIONAL === $this->format) {
            return $util->formatOutOfCountryCallingNumber($value, $this->defaultRegion);
        }

        return $util->format($value, $this->format);
    }

    /**
     * @param mixed $value
     */
    public function reverseTransform($value): ?PhoneNumber
    {
        if (!$value) {
            return null;
        }

        $util = PhoneNumberUtil::getInstance();

        try {
            return $util->parse($value, $this->defaultRegion);
        } catch (NumberParseException $e) {
            throw new TransformationFailedException($e->getMessage(), $e->getCode(), $e);
        }
    }
}
