<?php

/*
 * This file is part of the Klipper package.
 *
 * (c) François Pluchino <francois.pluchino@klipper.dev>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Klipper\Bundle\LockBundle\Command;

use Doctrine\DBAL\Exception\TableExistsException;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Lock\Store\PdoStore;

/**
 * Init the system permissions.
 *
 * @author François Pluchino <francois.pluchino@klipper.dev>
 */
class InitLockStoreCommand extends Command
{
    private PdoStore $store;

    public function __construct(PdoStore $store)
    {
        parent::__construct();

        $this->store = $store;
    }

    protected function configure(): void
    {
        $this
            ->setName('init:lock-store')
            ->setDescription('Init the lock store')
        ;
    }

    /**
     * @throws \Exception
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        try {
            $this->store->createTable();
            $output->writeln('  The lock store have been initialized');
        } catch (TableExistsException $exception) {
            $output->writeln('  The lock store is already up to date');
        }

        return 0;
    }
}
