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

use Klipper\Bundle\DoctrineExtensionsExtraBundle\KlipperDoctrineExtensionsExtraBundle;
use Klipper\Bundle\MetadataBundle\KlipperMetadataBundle;
use Klipper\Component\DoctrineExtensionsExtra\Filter\Listener\AbstractFilterSubscriber;
use Klipper\Component\Routing\RoutingInterface;
use Klipper\Component\Security\Identity\SecurityIdentityInterface;
use Symfony\Bundle\TwigBundle\TwigBundle;
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

        $this->configPortalDoctrine($loader);
        $this->configPortalRouting($loader);
        $this->configPortalContext($container, $loader, $config['context']);
        $this->configTwig($container, $loader, $config['twig']);
    }

    /**
     * @throws
     */
    protected function configPortalDoctrine(LoaderInterface $loader): void
    {
        if (class_exists(KlipperDoctrineExtensionsExtraBundle::class)
            && class_exists(KlipperMetadataBundle::class)
        ) {
            $loader->load('portal_doctrine.xml');
        }
    }

    /**
     * @throws
     */
    protected function configPortalRouting(LoaderInterface $loader): void
    {
        if (interface_exists(RoutingInterface::class)) {
            $loader->load('portal_routing.xml');
        }
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

            if (class_exists(AbstractFilterSubscriber::class)) {
                $loader->load('security_portal_orm_filter_listener.xml');
            }

            if (class_exists(KlipperDoctrineExtensionsExtraBundle::class)) {
                $loader->load('portal_manager.xml');
            }
        }

        $container->getDefinition('klipper_portal.portal_context.helper')
            ->replaceArgument(4, $config['security']['permission_name'])
        ;
    }

    /**
     * @throws
     */
    protected function configTwig(ContainerBuilder $container, LoaderInterface $loader, array $config): void
    {
        if (class_exists(TwigBundle::class)) {
            $loader->load('portal_twig.xml');

            $def = $container->getDefinition('klipper_portal.twig.error_renderer.html');
            $def->replaceArgument(4, $config['template_base_path']);
        }
    }
}
