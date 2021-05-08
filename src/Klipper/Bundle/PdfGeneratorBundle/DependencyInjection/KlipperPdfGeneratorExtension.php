<?php

/*
 * This file is part of the Klipper package.
 *
 * (c) François Pluchino <francois.pluchino@klipper.dev>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Klipper\Bundle\PdfGeneratorBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

/**
 * This is the class that loads and manages your bundle configuration.
 *
 * @author François Pluchino <francois.pluchino@klipper.dev>
 */
class KlipperPdfGeneratorExtension extends Extension
{
    /**
     * @throws \Exception
     */
    public function load(array $configs, ContainerBuilder $container): void
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $loader = new Loader\XmlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('generator.xml');

        $this->configurePdf($container, $config['pdf']);
        $this->configureGenerator($container, $config['generator']);
    }

    private function configurePdf(ContainerBuilder $container, array $config): void
    {
        $container->getDefinition('klipper_pdf_generator.pdf')
            ->replaceArgument(1, $config['temp_dir'])
        ;
    }

    private function configureGenerator(ContainerBuilder $container, array $config): void
    {
        $this->configureChromeGenerator($container, $config['google_chrome']);
    }

    private function configureChromeGenerator(ContainerBuilder $container, array $config): void
    {
        $container->getDefinition('klipper_pdf_generator.generator.google_chrome')
            ->replaceArgument(0, $config['bin_path'])
            ->replaceArgument(1, $config['temp_dir'])
            ->replaceArgument(2, $config['default_options'])
        ;
    }
}
