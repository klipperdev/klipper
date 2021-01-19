<?php

/*
 * This file is part of the Klipper package.
 *
 * (c) François Pluchino <francois.pluchino@klipper.dev>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Klipper\Component\DoctrineChoice\Listener\Traits;

use Doctrine\ORM\EntityManagerInterface;
use Klipper\Component\DoctrineChoice\Model\ChoiceInterface;

/**
 * Helper to get the doctrine choice in Doctrine listener or subscriber.
 *
 * @author François Pluchino <francois.pluchino@klipper.dev>
 */
trait DoctrineListenerChoiceTrait
{
    protected array $doctrineChoices = [];

    protected function getChoice(EntityManagerInterface $em, string $type, string $value): ?ChoiceInterface
    {
        if (!isset($this->doctrineChoices[$type])) {
            $this->doctrineChoices[$type] = [];
            $res = $em->getRepository(ChoiceInterface::class)->findBy([
                'type' => $type,
            ]);

            /** @var ChoiceInterface $item */
            foreach ($res as $item) {
                $this->doctrineChoices[$type][$item->getValue()] = $item;
            }
        }

        return $this->doctrineChoices[$type][$value] ?? null;
    }
}
