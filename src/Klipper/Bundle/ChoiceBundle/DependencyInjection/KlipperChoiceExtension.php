<?php

/*
 * This file is part of the Klipper package.
 *
 * (c) François Pluchino <francois.pluchino@klipper.dev>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Klipper\Bundle\ChoiceBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

/**
 * This is the class that loads and manages your bundle configuration.
 *
 * @author François Pluchino <francois.pluchino@klipper.dev>
 */
class KlipperChoiceExtension extends Extension
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

        $this->configExtendableChoices($container, $config['extendable_choices']);
    }

    /**
     * Configure the extendable choices.
     *
     * @param ContainerBuilder $container The container builder
     * @param array            $configs   The config of extendable choices
     */
    protected function configExtendableChoices(ContainerBuilder $container, array $configs): void
    {
        $container->setParameter('klipper_choice.extendable_choice.configs', $configs);
    }
}
