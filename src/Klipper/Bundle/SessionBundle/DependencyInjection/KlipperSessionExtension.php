<?php

/*
 * This file is part of the Klipper package.
 *
 * (c) François Pluchino <francois.pluchino@klipper.dev>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Klipper\Bundle\SessionBundle\DependencyInjection;

use Klipper\Bundle\SessionBundle\Exception\InvalidConfigurationException;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

/**
 * This is the class that loads and manages your bundle configuration.
 *
 * @author François Pluchino <francois.pluchino@klipper.dev>
 */
class KlipperSessionExtension extends Extension
{
    /**
     * {@inheritdoc}
     *
     * @throws \Exception
     */
    public function load(array $configs, ContainerBuilder $container): void
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $loader = new Loader\XmlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));

        // Session
        if ($config['pdo']['enabled']) {
            $loader->load('pdo_session.xml');
            $this->configPdo($container, $config['pdo']);
        }
    }

    /**
     * Configure the PDO Session Handler.
     */
    protected function configPdo(ContainerBuilder $container, array $config): void
    {
        if (null === $config['dsn']) {
            throw new InvalidConfigurationException('The "pdo.dsn" parameter under the "klipper_session" section in the config must be set in order');
        }

        $container->setParameter('klipper_session.pdo.dsn', $config['dsn']);
        $container->setParameter('klipper_session.pdo.db_options', $config['db_options']);
    }
}
