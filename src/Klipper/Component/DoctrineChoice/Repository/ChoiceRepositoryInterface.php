<?php

/*
 * This file is part of the Klipper package.
 *
 * (c) François Pluchino <francois.pluchino@klipper.dev>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Klipper\Component\DoctrineChoice\Repository;

use Doctrine\Persistence\ObjectRepository;
use Klipper\Component\DoctrineChoice\Model\ChoiceInterface;

/**
 * @author François Pluchino <francois.pluchino@klipper.dev>
 */
interface ChoiceRepositoryInterface extends ObjectRepository
{
    /**
     * @param string[] $types The choice types
     *
     * @return ChoiceInterface[] The map of crm choice ids (`<type>|<value>`) and choice instances
     */
    public function findChoices(array $types): array;
}
