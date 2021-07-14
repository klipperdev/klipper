<?php

/*
 * This file is part of the Klipper package.
 *
 * (c) François Pluchino <francois.pluchino@klipper.dev>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Klipper\Component\DoctrineChoice;

use Klipper\Component\DoctrineChoice\Model\ChoiceInterface;

/**
 * @author François Pluchino <francois.pluchino@klipper.dev>
 */
interface ChoiceManagerInterface
{
    /**
     * @return ChoiceInterface[]
     */
    public function getChoices(string $type): array;

    public function getChoice(string $type, ?string $value): ?ChoiceInterface;
}
