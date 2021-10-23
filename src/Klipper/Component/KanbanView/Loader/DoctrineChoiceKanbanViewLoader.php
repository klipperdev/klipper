<?php

/*
 * This file is part of the Klipper package.
 *
 * (c) François Pluchino <francois.pluchino@klipper.dev>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Klipper\Component\KanbanView\Loader;

use Klipper\Component\DoctrineChoice\ChoiceManagerInterface;
use Klipper\Component\DoctrineChoice\Model\ChoiceInterface;
use Klipper\Component\KanbanView\KanbanView;
use Klipper\Component\KanbanView\KanbanViewColumn;

/**
 * @author François Pluchino <francois.pluchino@klipper.dev>
 */
class DoctrineChoiceKanbanViewLoader implements KanbanViewLoaderInterface
{
    private ChoiceManagerInterface $choiceManager;

    public function __construct(ChoiceManagerInterface $choiceManager)
    {
        $this->choiceManager = $choiceManager;
    }

    public function supports(string $type, string $name): bool
    {
        return ('choice' === $type || is_a($type, ChoiceInterface::class, true))
            && !empty($this->choiceManager->getChoices($name))
        ;
    }

    public function load(string $type, string $name): array
    {
        $view = new KanbanView($type, $name);

        foreach ($this->choiceManager->getChoices($name) as $choice) {
            $view->addColumn(
                (new KanbanViewColumn($choice->getValue(), $choice->getLabel()))
                    ->setColor($choice->getColor())
                    ->setIcon($choice->getIcon())
                    ->setId(method_exists($choice, 'getId') ? $choice->getId() : null)
            );
        }

        return [$view];
    }
}
