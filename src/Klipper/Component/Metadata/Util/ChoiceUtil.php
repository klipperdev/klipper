<?php

/*
 * This file is part of the Klipper package.
 *
 * (c) François Pluchino <francois.pluchino@klipper.dev>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Klipper\Component\Metadata\Util;

use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * @author François Pluchino <francois.pluchino@klipper.dev>
 */
abstract class ChoiceUtil
{
    /**
     * Get the translated message.
     *
     * @param TranslatorInterface $translator        The translator
     * @param array               $identifiers       The list identifier
     * @param null|string         $translationDomain The translation domain
     */
    public static function getTrans(TranslatorInterface $translator, array $identifiers, ?string $translationDomain): array
    {
        if (null === $translationDomain) {
            $values = $identifiers;
        } else {
            $values = [];

            foreach ($identifiers as $key => $value) {
                $transParam = [
                    '{{ value }}' => $key,
                ];

                if (\is_array($value)) {
                    $values[$translator->trans($key, $transParam, $translationDomain)] = static::getTrans(
                        $translator,
                        $value,
                        $translationDomain
                    );
                } else {
                    $values[$key] = $translator->trans($value, $transParam, $translationDomain);
                }
            }
        }

        return $values;
    }

    /**
     * Get the translated placeholder.
     *
     * @param TranslatorInterface $translator        The translator
     * @param null|string         $placeholder       The placeholder
     * @param null|string         $translationDomain The translation domain
     */
    public static function getTransPlaceholder(TranslatorInterface $translator, ?string $placeholder, ?string $translationDomain): ?string
    {
        return null !== $placeholder && null !== $translationDomain
            ? $translator->trans($placeholder)
            : $placeholder;
    }

    /**
     * Humanize the choice value.
     *
     * @param string $text The text
     */
    public static function humanize(string $text): string
    {
        return ucfirst(strtolower(trim(preg_replace(['/([A-Z])/', '/[_\s]+/'], ['_$1', ' '], $text))));
    }
}
