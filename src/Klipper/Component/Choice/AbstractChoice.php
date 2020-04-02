<?php

/*
 * This file is part of the Klipper package.
 *
 * (c) François Pluchino <francois.pluchino@klipper.dev>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Klipper\Component\Choice;

use Klipper\Component\Choice\Util\ChoiceUtil;

/**
 * Abstract class of Choice.
 *
 * @author François Pluchino <francois.pluchino@klipper.dev>
 */
abstract class AbstractChoice implements ChoiceInterface
{
    /**
     * {@inheritdoc}
     */
    public static function getValues(): array
    {
        return ChoiceUtil::getValues(static::listIdentifiers());
    }

    /**
     * {@inheritdoc}
     */
    public static function getTranslationDomain(): string
    {
        return 'choices';
    }
}
