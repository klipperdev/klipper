<?php

/*
 * This file is part of the Klipper package.
 *
 * (c) François Pluchino <francois.pluchino@klipper.dev>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Klipper\Component\Choice\Util;

/**
 * Choice Available Utils.
 *
 * @author François Pluchino <francois.pluchino@klipper.dev>
 */
abstract class ChoiceAvailableUtil
{
    /**
     * Generate the available choice item list.
     *
     * @param array $items      The items
     * @param array $availables The available items
     *
     * @return array
     */
    public static function generateAvailableList(array $items, array $availables = []): array
    {
        if (0 === \count($availables)) {
            return $items;
        }

        $data = [];

        foreach ($availables as $available) {
            if (isset($items[$available])) {
                $data[$available] = $items[$available];
            }
        }

        asort($data);

        return $data;
    }
}
