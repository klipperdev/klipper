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

use Doctrine\ORM\EntityManagerInterface;
use Klipper\Component\DoctrineExtensions\Util\SqlFilterUtil;
use Klipper\Component\SecurityOauth\Model\OauthAccessTokenInterface;
use Klipper\Component\SecurityOauth\Model\OauthAuthCodeInterface;
use Klipper\Component\SecurityOauth\Model\OauthRefreshTokenInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

/**
 * @author François Pluchino <francois.pluchino@klipper.dev>
 */
class ClearTokenCommand extends Command
{
    private EntityManagerInterface $em;

    public function __construct(EntityManagerInterface $em)
    {
        parent::__construct();

        $this->em = $em;
    }

    protected function configure(): void
    {
        $this
            ->setName('oauth:token:clear')
            ->setDescription('Remove all expired security oauth tokens')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): void
    {
        $io = new SymfonyStyle($input, $output);
        $io->comment('Clearing the expired oauth auth codes, access tokens and refresh tokens');

        SqlFilterUtil::disableFilters($this->em, ['expirable']);
        $this->clearTokens(OauthAuthCodeInterface::class);
        $this->clearTokens(OauthAccessTokenInterface::class);
        $this->clearTokens(OauthRefreshTokenInterface::class);

        $io->success('Expired Oauth tokens and auth codes were successfully cleared');
    }

    private function clearTokens(string $class): void
    {
        $this->em->createQueryBuilder()
            ->delete($class, 'o')
            ->where('o.expiresAt <= CURRENT_TIMESTAMP()')
            ->getQuery()
            ->execute()
        ;
    }
}
