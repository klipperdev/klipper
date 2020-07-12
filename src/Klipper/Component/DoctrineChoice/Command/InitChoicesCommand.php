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

use Klipper\Component\DataLoader\Exception\InvalidArgumentException;
use Klipper\Component\DoctrineChoice\DataLoader\YamlChoiceLoader;
use Klipper\Component\DoctrineChoice\Model\ChoiceInterface;
use Klipper\Component\Resource\Domain\DomainManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Init the system choices.
 *
 * @author François Pluchino <francois.pluchino@klipper.dev>
 */
class InitChoicesCommand extends Command
{
    private DomainManagerInterface $domainManager;

    private string $projectDir;

    public function __construct(DomainManagerInterface $domainManager, string $projectDir)
    {
        parent::__construct();

        $this->domainManager = $domainManager;
        $this->projectDir = $projectDir;
    }

    protected function configure(): void
    {
        $this
            ->setName('init:choices')
            ->setDescription('Init the system choices')
        ;
    }

    /**
     * @throws \Exception
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $domainChoice = $this->domainManager->get(ChoiceInterface::class);
        $loader = new YamlChoiceLoader($domainChoice);
        $file = $this->projectDir.'/config/data/choices.yaml';

        if (!file_exists($file)) {
            $output->writeln(sprintf('  The system choices are not defined in file "%s"', $file));

            return 0;
        }

        if (!$loader->supports($file)) {
            throw new InvalidArgumentException('The resource is not supported by this data loader');
        }

        $loader->load($file);

        if ($loader->hasNewEntities() || $loader->hasUpdatedEntities()) {
            $output->writeln('  The system choices have been initialized');
        } else {
            $output->writeln('  The system choices are already up to date');
        }

        return 0;
    }
}
