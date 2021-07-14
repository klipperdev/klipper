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

use Doctrine\ORM\EntityManagerInterface;
use Klipper\Component\DoctrineChoice\Model\ChoiceInterface;

/**
 * @author François Pluchino <francois.pluchino@klipper.dev>
 */
class ChoiceManager implements ChoiceManagerInterface
{
    private EntityManagerInterface $em;

    private array $orderBy;

    private array $doctrineChoices = [];

    public function __construct(EntityManagerInterface $em, ?array $orderBy = null)
    {
        $this->em = $em;
        $this->orderBy = $orderBy ?? ['position' => 'asc', 'value' => 'asc'];
    }

    public function getChoices(string $type): array
    {
        if (!isset($this->doctrineChoices[$type])) {
            $this->doctrineChoices[$type] = [];
            $res = $this->em->getRepository(ChoiceInterface::class)->findBy([
                'type' => $type,
            ], $this->orderBy);

            /** @var ChoiceInterface $item */
            foreach ($res as $item) {
                $this->doctrineChoices[$type][$item->getValue()] = $item;
            }
        }

        return $this->doctrineChoices[$type];
    }

    public function getChoice(string $type, ?string $value): ?ChoiceInterface
    {
        $choices = $this->getChoices($type);

        // Select the first choice
        if (null === $value) {
            $values = array_keys($choices);

            if (\count($values) > 0) {
                $value = $values[0];
            }
        }

        return $choices[$value] ?? null;
    }
}
