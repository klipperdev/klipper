<?php

/*
 * This file is part of the Klipper package.
 *
 * (c) François Pluchino <francois.pluchino@klipper.dev>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Klipper\Component\Phone\Validator\Constraints;

use libphonenumber\NumberParseException;
use libphonenumber\PhoneNumber;
use libphonenumber\PhoneNumberFormat;
use libphonenumber\PhoneNumberType;
use libphonenumber\PhoneNumberUtil;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

/**
 * @author François Pluchino <francois.pluchino@klipper.dev>
 */
class PhoneValidator extends ConstraintValidator
{
    /**
     * @param mixed $value
     */
    public function validate($value, Constraint $constraint): void
    {
        if (null === $value || '' === $value) {
            return;
        }

        if (!is_scalar($value) && !(\is_object($value) && method_exists($value, '__toString'))) {
            throw new UnexpectedTypeException($value, 'string');
        }

        $phoneUtil = PhoneNumberUtil::getInstance();
        /* @var Phone $constraint */

        if (!$value instanceof PhoneNumber) {
            $value = (string) $value;

            try {
                $phoneNumber = $phoneUtil->parse($value, $constraint->defaultRegion);
            } catch (NumberParseException $e) {
                $this->addViolation($value, $constraint);

                return;
            }
        } else {
            $phoneNumber = $value;
            $value = $phoneUtil->format($phoneNumber, PhoneNumberFormat::INTERNATIONAL);
        }

        if (false === $phoneUtil->isValidNumber($phoneNumber)) {
            $this->addViolation($value, $constraint);

            return;
        }

        switch ($constraint->getType()) {
            case Phone::FIXED_LINE:
                $validTypes = [PhoneNumberType::FIXED_LINE, PhoneNumberType::FIXED_LINE_OR_MOBILE];

                break;
            case Phone::MOBILE:
                $validTypes = [PhoneNumberType::MOBILE, PhoneNumberType::FIXED_LINE_OR_MOBILE];

                break;
            case Phone::PAGER:
                $validTypes = [PhoneNumberType::PAGER];

                break;
            case Phone::PERSONAL_NUMBER:
                $validTypes = [PhoneNumberType::PERSONAL_NUMBER];

                break;
            case Phone::PREMIUM_RATE:
                $validTypes = [PhoneNumberType::PREMIUM_RATE];

                break;
            case Phone::SHARED_COST:
                $validTypes = [PhoneNumberType::SHARED_COST];

                break;
            case Phone::TOLL_FREE:
                $validTypes = [PhoneNumberType::TOLL_FREE];

                break;
            case Phone::UAN:
                $validTypes = [PhoneNumberType::UAN];

                break;
            case Phone::VOIP:
                $validTypes = [PhoneNumberType::VOIP];

                break;
            case Phone::VOICEMAIL:
                $validTypes = [PhoneNumberType::VOICEMAIL];

                break;
            default:
                $validTypes = [];

                break;
        }

        if (\count($validTypes)) {
            $type = $phoneUtil->getNumberType($phoneNumber);

            if (!\in_array($type, $validTypes, true)) {
                $this->addViolation($value, $constraint);

                return;
            }
        }
    }

    /**
     * Add a violation.
     *
     * @param mixed $value      The value that should be validated
     * @param Phone $constraint The constraint for the validation
     */
    private function addViolation($value, Phone $constraint): void
    {
        $this->context->buildViolation($constraint->getMessage())
            ->setParameter('{{ type }}', $constraint->getType())
            ->setParameter('{{ value }}', $value)
            ->setTranslationDomain('validators')
            ->addViolation()
        ;
    }
}
