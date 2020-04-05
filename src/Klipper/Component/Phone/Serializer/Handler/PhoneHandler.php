<?php

/*
 * This file is part of the Klipper package.
 *
 * (c) François Pluchino <francois.pluchino@klipper.dev>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Klipper\Component\Phone\Serializer\Handler;

use JMS\Serializer\JsonDeserializationVisitor;
use JMS\Serializer\Visitor\SerializationVisitorInterface;
use JMS\Serializer\XmlDeserializationVisitor;
use libphonenumber\PhoneNumber;
use libphonenumber\PhoneNumberFormat;
use libphonenumber\PhoneNumberUtil;

/**
 * @author François Pluchino <francois.pluchino@klipper.dev>
 */
class PhoneHandler
{
    /**
     * @var PhoneNumberUtil
     */
    private $phoneNumberUtil;

    /**
     * Constructor.
     *
     * @param PhoneNumberUtil $phoneNumberUtil The phone number utility
     */
    public function __construct(PhoneNumberUtil $phoneNumberUtil)
    {
        $this->phoneNumberUtil = $phoneNumberUtil;
    }

    /**
     * Serialize a phone number.
     *
     * @param SerializationVisitorInterface $visitor     The serialization visitor
     * @param PhoneNumber                   $phoneNumber The phone number
     * @param array                         $type        The type
     */
    public function serializePhoneNumber(SerializationVisitorInterface $visitor, PhoneNumber $phoneNumber, array $type): ?string
    {
        $formatted = $this->phoneNumberUtil->format($phoneNumber, PhoneNumberFormat::E164);

        return $visitor->visitString($formatted, $type);
    }

    /**
     * Deserialize a phone number from JSON.
     *
     * @param JsonDeserializationVisitor $visitor The deserialization visitor
     * @param null|string                $data    The data
     * @param array                      $type    The type
     *
     * @throws
     */
    public function deserializePhoneNumberFromJson(JsonDeserializationVisitor $visitor, $data, array $type): ?PhoneNumber
    {
        return null !== $data
            ? $this->phoneNumberUtil->parse($data, PhoneNumberUtil::UNKNOWN_REGION)
            : null;
    }

    /**
     * Deserialize a phone number from XML.
     *
     * @param XmlDeserializationVisitor $visitor The deserialization visitor
     * @param \SimpleXMLElement         $data    The data
     * @param array                     $type    The type
     *
     * @throws
     *
     * @return null|PhoneNumber
     */
    public function deserializePhoneNumberFromXml(XmlDeserializationVisitor $visitor, $data, array $type)
    {
        $attributes = $data->attributes();

        if ((isset($attributes['nil'][0]) && 'true' === (string) $attributes['nil'][0])
                || (isset($attributes['xsi:nil'][0]) && 'true' === (string) $attributes['xsi:nil'][0])) {
            return null;
        }

        return $this->phoneNumberUtil->parse($data, PhoneNumberUtil::UNKNOWN_REGION);
    }
}
