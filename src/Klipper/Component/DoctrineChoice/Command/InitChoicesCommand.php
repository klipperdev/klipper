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
use Symfony\Component\Finder\Finder;

/**
 * Init the system choices.
 *
 * @author François Pluchino <francois.pluchino@klipper.dev>
 */
class InitChoicesCommand extends Command
{
    private DomainManagerInterface $domainManager;

    private string $projectDir;

    private array $bundles;

    public function __construct(DomainManagerInterface $domainManager, string $projectDir, array $bundles)
    {
        parent::__construct();

        $this->domainManager = $domainManager;
        $this->projectDir = $projectDir;
        $this->bundles = $bundles;
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
        $finder = (new Finder())
            ->ignoreVCS(true)
            ->in($this->getBundlePaths())
            ->name([
                'choices.yaml',
                'choices_*.yaml',
            ])
        ;

        if (0 === $finder->count()) {
            $output->writeln('  No system choices are defined in "<project_dir>/config/data" or "<bundle>/Resources/config/data"');

            return 0;
        }

        $domainChoice = $this->domainManager->get(ChoiceInterface::class);
        $loader = new YamlChoiceLoader($domainChoice);
        $updated = false;

        foreach ($finder->files() as $file) {
            $filename = $file->getPathname();

            if (!$loader->supports($filename)) {
                throw new InvalidArgumentException(sprintf(
                    'The resource "%s" is not supported by this data loader',
                    $filename
                ));
            }

            $loader->load($filename);
            $updated = $updated || $loader->hasNewEntities() || $loader->hasUpdatedEntities();
        }

        if ($updated) {
            $output->writeln('  The system choices have been initialized');
        } else {
            $output->writeln('  The system choices are already up to date');
        }

        return 0;
    }

    /**
     * @return string[]
     */
    protected function getBundlePaths(): array
    {
        $paths = [];

        foreach ($this->bundles as $bundle) {
            $ref = new \ReflectionClass($bundle);
            $path = \dirname($ref->getFileName()).'/Resources/config/data';

            if (is_dir($path)) {
                $paths[] = $path;
            }
        }

        $paths[] = $this->projectDir.'/config/data';

        return $paths;
    }
}
