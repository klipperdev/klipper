<?php

/*
 * This file is part of the Klipper package.
 *
 * (c) François Pluchino <francois.pluchino@klipper.dev>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Klipper\Bundle\PortalBundle\DependencyInjection;

use Klipper\Component\Security\Identity\SecurityIdentityInterface;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

/**
 * This is the class that loads and manages your bundle configuration.
 *
 * @author François Pluchino <francois.pluchino@klipper.dev>
 */
class KlipperPortalExtension extends Extension
{
    /**
     * @throws \Exception
     */
    public function load(array $configs, ContainerBuilder $container): void
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $loader = new Loader\XmlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));

        $this->configPortalContext($container, $loader, $config['context']);
    }

    /**
     * @throws
     */
    protected function configPortalContext(ContainerBuilder $container, LoaderInterface $loader, array $config): void
    {
        $loader->load('portal_context.xml');

        if (interface_exists(SecurityIdentityInterface::class)) {
            $loader->load('security_portal_context.xml');

            foreach ($config['security']['expression']['functions'] as $function => $enabled) {
                if ($enabled) {
                    $loader->load(sprintf('expression_function_%s.xml', $function));
                }
            }
        }

        $container->getDefinition('klipper_portal.portal_context.helper')
            ->replaceArgument(4, $config['security']['permission_name'])
        ;
    }
}
