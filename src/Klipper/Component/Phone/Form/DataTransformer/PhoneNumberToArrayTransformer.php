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
 * Phone number to array.
 *
 * @author François Pluchino <francois.pluchino@klipper.dev>
 */
class PhoneNumberToArrayTransformer implements DataTransformerInterface
{
    private array $countryChoices;

    private ?string $defaultCountry = null;

    /**
     * @param array       $countryChoices The country choice
     * @param null|string $defaultCountry The default country
     */
    public function __construct(array $countryChoices, ?string $defaultCountry = null)
    {
        $this->countryChoices = array_values($countryChoices);
        $this->defaultCountry = $defaultCountry;
    }

    /**
     * @param mixed $value
     */
    public function transform($value): array
    {
        if (null === $value) {
            return [
                'country' => $this->getDefaultCountry(),
                'number' => '',
            ];
        }
        if (!$value instanceof PhoneNumber) {
            throw new TransformationFailedException('Expected a \libphonenumber\PhoneNumber.');
        }

        $util = PhoneNumberUtil::getInstance();

        if (!\in_array($util->getRegionCodeForNumber($value), $this->countryChoices, true)) {
            throw new TransformationFailedException('Invalid country.');
        }

        return [
            'country' => $util->getRegionCodeForNumber($value),
            'number' => $util->format($value, PhoneNumberFormat::NATIONAL),
        ];
    }

    /**
     * @param mixed $value
     */
    public function reverseTransform($value): ?PhoneNumber
    {
        if (!$value) {
            return null;
        }

        if (!\is_array($value)
                || !\array_key_exists('number', $value)
                || !\array_key_exists('country', $value)) {
            throw new TransformationFailedException('Expected an array.');
        }

        if ('' === trim($value['number'])) {
            return null;
        }

        $util = PhoneNumberUtil::getInstance();

        try {
            $phoneNumber = $util->parse($value['number'], $value['country']);
        } catch (NumberParseException $e) {
            throw new TransformationFailedException($e->getMessage(), $e->getCode(), $e);
        }

        if (!\in_array($util->getRegionCodeForNumber($phoneNumber), $this->countryChoices, true)) {
            throw new TransformationFailedException('Invalid country.');
        }

        return $phoneNumber;
    }

    /**
     * Get the default value of country.
     */
    protected function getDefaultCountry(): string
    {
        $country = $this->defaultCountry ?? strtoupper(\Locale::getDefault());

        if (false !== $pos = strpos($country, '_')) {
            $country = substr($country, $pos + 1);
        }

        return \in_array($country, $this->countryChoices, true)
            ? $country
            : '';
    }
}
