<?php

/*
 * This file is part of the Klipper package.
 *
 * (c) François Pluchino <francois.pluchino@klipper.dev>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Klipper\Component\SecurityOauth\Command;

use Defuse\Crypto\Key;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @author François Pluchino <francois.pluchino@klipper.dev>
 */
class GenerateOauthEncryptionKeyCommand extends Command
{
    protected function configure(): void
    {
        $this
            ->setName('generate:oauth:encryption-key')
            ->setDescription('Generate the encryption key for the security oauth (env OAUTH2_ENCRYPTION_KEY)')
        ;
    }

    /**
     * @throws
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $key = Key::createNewRandomKey();
        $output->writeln($key->saveToAsciiSafeString());

        return 0;
    }
}
