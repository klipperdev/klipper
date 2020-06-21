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

use Klipper\Component\DataLoader\Exception\ConsoleResourceException;
use Klipper\Component\DataLoaderSecurity\Command\InitOrganizationCommand;
use Klipper\Component\Resource\Domain\DomainManagerInterface;
use Klipper\Component\Security\Model\OrganizationInterface;
use Klipper\Component\Security\Model\Traits\OrganizationalInterface;
use Klipper\Component\SecurityOauth\Exception\RuntimeException;
use Klipper\Component\SecurityOauth\Model\OauthClientInterface;
use Klipper\Contracts\Model\IdInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

/**
 * @author François Pluchino <francois.pluchino@klipper.dev>
 */
class GenerateOauthClientCommand extends Command
{
    public const VALID_GRANTS = [
        'authorization_code',
        'client_credentials',
        'implicit',
        'password',
        'refresh_token',
    ];

    private DomainManagerInterface $domainManager;

    public function __construct(DomainManagerInterface $domainManager)
    {
        parent::__construct();

        $this->domainManager = $domainManager;
    }

    protected function configure(): void
    {
        $orgName = class_exists(InitOrganizationCommand::class)
            ? InitOrganizationCommand::ORGANIZATION_NAME : null;

        $this
            ->setName('generate:oauth:client')
            ->setDescription('Generate the oauth client')
            ->addArgument('name', InputArgument::OPTIONAL, 'The unique name of the oauth client', 'app-localhost')
            ->addOption('org', '-o', InputOption::VALUE_OPTIONAL, 'The organization name of the oauth client', $orgName)
            ->addOption('secret', '-S', InputOption::VALUE_REQUIRED, 'The secret for the oauth client, null for automatic generation')
            ->addOption('disabled', '-D', InputOption::VALUE_NONE, 'Disable by default the oauth client')
            ->addOption('grant', '-g', InputOption::VALUE_IS_ARRAY | InputOption::VALUE_REQUIRED, 'The oauth grants', ['password', 'refresh_token'])
            ->addOption('redirect-uri', '-R', InputOption::VALUE_IS_ARRAY | InputOption::VALUE_REQUIRED, 'The oauth redirect uri', ['https://localhost'])
        ;
    }

    /**
     * @throws
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $style = new SymfonyStyle($input, $output);

        $name = (string) $input->getArgument('name');
        $orgName = $input->getOption('org');
        $secret = $input->getOption('secret');
        $disabled = (bool) $input->getOption('secret');
        $grants = (array) $this->getGrants($input);
        $redirectUri = (array) $input->getOption('redirect-uri');

        $ocDomain = $this->domainManager->get(OauthClientInterface::class);
        $orgDomain = $this->domainManager->get(OrganizationInterface::class);

        /** @var null|OauthClientInterface $client */
        $client = $ocDomain->getRepository()->findOneBy(['name' => $name]);

        if (null !== $client) {
            $style->note('Oauth client was already generated');
            $this->displayClient($style, $client);

            return 0;
        }

        $client = $ocDomain->newInstance();
        $client->setGrantTypes($grants);
        $client->setName($name);
        $client->setEnabled(!$disabled);
        $client->setSecret($secret);
        $client->setRedirectUri($redirectUri);

        if (null !== $orgName && $client instanceof OrganizationalInterface) {
            $org = $orgDomain->getRepository()->findOneBy(['name' => $orgName]);

            if ($org instanceof OrganizationInterface) {
                $client->setOrganization($org);
            }
        }

        $res = $ocDomain->create($client);

        if (!$res->isValid()) {
            throw new ConsoleResourceException($res);
        }

        $style->success('Oauth client is generated with successfully');
        $this->displayClient($style, $client);

        return 0;
    }

    private function displayClient(OutputInterface $output, OauthClientInterface $client): void
    {
        $table = new Table($output);
        $table->setRows([
            ['Name:', $client->getName()],
            ['Id:', $client instanceof IdInterface ? $client->getId() : '?'],
            ['Grant types:', implode(', ', $client->getGrantTypes())],
            ['Redirection URI:', implode(', ', $client->getRedirectUri())],
            ['Client id:', $client->getClientId()],
            ['Secret:', $client->getSecret()],
        ]);
        $table->render();
    }

    private function getGrants(InputInterface $input): array
    {
        /** @var string[] */
        $grants = (array) $input->getOption('grant');

        foreach ($grants as $grant) {
            if (!\in_array($grant, static::VALID_GRANTS, true)) {
                throw new RuntimeException(sprintf(
                    'The "%s" grant is invalid. Only available values: "%s"',
                    $grant,
                    implode('", "', static::VALID_GRANTS)
                ));
            }
        }

        return $grants;
    }
}
