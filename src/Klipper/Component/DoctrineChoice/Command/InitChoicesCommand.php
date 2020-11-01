<?php

/*
 * This file is part of the Klipper package.
 *
 * (c) François Pluchino <francois.pluchino@klipper.dev>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Klipper\Component\DoctrineChoice\Command;

use Klipper\Component\DataLoader\Command\AbstractDataLoaderCommand;
use Klipper\Component\DataLoader\DataLoaderInterface;
use Klipper\Component\DoctrineChoice\DataLoader\YamlChoiceLoader;
use Klipper\Component\DoctrineChoice\Model\ChoiceInterface;

/**
 * Init the system choices.
 *
 * @author François Pluchino <francois.pluchino@klipper.dev>
 */
class InitChoicesCommand extends AbstractDataLoaderCommand
{
    protected function configure(): void
    {
        $this
            ->setName('init:choices')
            ->setDescription('Init the system choices')
        ;
    }

    protected function getDataLoader(): DataLoaderInterface
    {
        return new YamlChoiceLoader($this->domainManager->get(ChoiceInterface::class));
    }

    protected function getFindFileNames(): array
    {
        return [
            'choices.yaml',
            'choices_*.yaml',
        ];
    }

    protected function getEmptyMessage(): string
    {
        return 'No system choices are defined';
    }

    protected function getInitializedMessage(): string
    {
        return 'The system choices have been initialized';
    }

    protected function getUpToDateMessage(): string
    {
        return 'The system choices are already up to date';
    }
}
