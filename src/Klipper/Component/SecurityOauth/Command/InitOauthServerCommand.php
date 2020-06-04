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

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Exception\RuntimeException;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Process\Process;

/**
 * @author François Pluchino <francois.pluchino@klipper.dev>
 */
class InitOauthServerCommand extends Command
{
    private string $publicKey;

    private string $privateKey;

    private ?string $passphrase;

    public function __construct(string $publicKey, string $privateKey, ?string $passphrase)
    {
        parent::__construct();

        $this->publicKey = $publicKey;
        $this->privateKey = $privateKey;
        $this->passphrase = $passphrase;
    }

    protected function configure(): void
    {
        $this
            ->setName('init:oauth')
            ->setDescription('Init the private and public keys of security oauth')
            ->addOption('force', 'f', InputOption::VALUE_NONE, 'Force to regenerate the keys')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $proc = new Process(['openssl', 'version']);
        $proc->run();

        if (!$proc->isSuccessful()) {
            throw new RuntimeException('Openssl binary must be installed');
        }

        $this->generatePrivateKey($output, $input->getOption('force'));
        $this->generatePublicKey($output, $input->getOption('force'));

        return 0;
    }

    protected function generatePrivateKey(OutputInterface $output, bool $force): void
    {
        if (!$force && file_exists($this->privateKey)) {
            $output->writeln('Private key for Oauth is already created');

            return;
        }

        if (!empty($this->passphrase)) {
            $proc = new Process(['openssl', 'genrsa', '-passout', 'pass:'.$this->passphrase, '-out', $this->privateKey, 2048]);
        } else {
            $proc = new Process(['openssl', 'genrsa', '-out', $this->privateKey, 2048]);
        }

        $proc->run();

        if (!$proc->isSuccessful()) {
            throw new RuntimeException('Private key cannot be created: '.PHP_EOL.$proc->getErrorOutput());
        }

        $output->writeln('Private key for Oauth was successfully created');
    }

    protected function generatePublicKey(OutputInterface $output, bool $force): void
    {
        if (!$force && file_exists($this->publicKey)) {
            $output->writeln('Public key for Oauth is already created');

            return;
        }

        if (!empty($this->passphrase)) {
            $proc = new Process(['openssl', 'rsa', '-in', $this->privateKey, '-passin', 'pass:'.$this->passphrase, '-pubout', '-out', $this->publicKey]);
        } else {
            $proc = new Process(['openssl', 'rsa', '-in', $this->privateKey, '-pubout', '-out', $this->publicKey]);
        }

        $proc->run();

        if (!$proc->isSuccessful()) {
            throw new RuntimeException('Private key cannot be created: '.PHP_EOL.$proc->getErrorOutput());
        }

        $output->writeln('Public key for Oauth was successfully created');
    }
}
