<?php

/*
 * This file is part of the Klipper package.
 *
 * (c) François Pluchino <francois.pluchino@klipper.dev>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Klipper\Bundle\DoctrineExtensionsExtraBundle\DependencyInjection;

use Klipper\Bundle\MetadataBundle\KlipperMetadataBundle;
use Klipper\Component\DefaultValue\ObjectBuilderInterface;
use Klipper\Component\DoctrineExtensions\ORM\Query\OrderByWalker;
use Klipper\Component\DoctrineExtensionsExtra\Form\Util\FormUtil;
use Klipper\Component\DoctrineExtensionsExtra\Listener\TablePrefixSubscriber;
use Klipper\Component\ExpressionLanguage\DependencyInjection\Compiler\AbstractExpressionLanguageProvidersPass;
use Klipper\Component\Metadata\ObjectMetadataInterface;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Loader;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * This is the class that loads and manages your bundle configuration.
 *
 * @author François Pluchino <francois.pluchino@klipper.dev>
 */
class KlipperDoctrineExtensionsExtraExtension extends Extension
{
    private array $entityManagers = [];

    private array $documentManagers = [];

    /**
     * @throws \Exception
     */
    public function load(array $configs, ContainerBuilder $container): void
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $loader = new Loader\XmlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('orm_listener.xml');
        $loader->load('request_query.xml');
        $loader->load('listener.xml');

        if (class_exists(ObjectBuilderInterface::class)) {
            $loader->load('listener_default_value.xml');
        }

        if (class_exists(Form::class)) {
            $loader->load('form.xml');
        }

        if (class_exists(FormUtil::class)) {
            $loader->load('form_klipper.xml');
        }

        if (class_exists(AbstractExpressionLanguageProvidersPass::class)) {
            $loader->load('expression_language.xml');
        }

        if (class_exists(KlipperMetadataBundle::class)) {
            $loader->load('request_query_metadata.xml');
        }

        if (interface_exists(ValidatorInterface::class)) {
            $loader->load('orm_validator.xml');

            if (class_exists(KlipperMetadataBundle::class)) {
                $loader->load('request_query_metadata_orm_validator.xml');
            }
        }

        $container->setParameter('klipper_doctrine_extensions_extra.locale_fallback', $config['locale_fallback']);
        $container->setParameter('klipper_doctrine_extensions_extra.pagination.default_size', $config['pagination']['default_size']);
        $container->setParameter('klipper_doctrine_extensions_extra.pagination.max_size', $config['pagination']['max_size']);

        $this->configureOrm($container, $config);
        $this->configureOdm($container, $config);
        $this->configureClass($container, $config);
        $this->configureRequestQuery($container);
    }

    /**
     * Validat the configuration.
     *
     * @param ContainerBuilder $container The container service
     */
    public function configValidate(ContainerBuilder $container): void
    {
        foreach (array_keys($this->entityManagers) as $name) {
            if (!$container->hasDefinition(sprintf('doctrine.dbal.%s_connection', $name))) {
                throw new \InvalidArgumentException(sprintf('Invalid %s config: DBAL connection "%s" not found', $this->getAlias(), $name));
            }
        }

        foreach (array_keys($this->documentManagers) as $name) {
            if (!$container->hasDefinition(sprintf('doctrine_mongodb.odm.%s_document_manager', $name))) {
                throw new \InvalidArgumentException(sprintf('Invalid %s config: document manager "%s" not found', $this->getAlias(), $name));
            }
        }
    }

    /**
     * Configure the ORM.
     *
     * @param ContainerBuilder $container The container service
     * @param array            $config    The config
     */
    private function configureOrm(ContainerBuilder $container, array $config): void
    {
        foreach ($config['orm']['listeners'] as $name => $listeners) {
            $this->configureDoctrineListeners($container, $listeners, $name, 'doctrine.event_subscriber');
            $this->entityManagers[$name] = $listeners;
        }

        if (!empty($config['orm']['table_prefix'])) {
            $prefix = $container->getParameterBag()->resolveValue($config['orm']['table_prefix']);
            $prefixDef = new Definition(TablePrefixSubscriber::class, [$prefix]);
            $prefixDef->setPublic(false);
            $prefixDef->addTag('doctrine.event_subscriber');
            $container->setDefinition('klipper_doctrine_extensions_extra.listener.table_prefix', $prefixDef);
        }
    }

    /**
     * Configure the ODM.
     *
     * @param ContainerBuilder $container The container service
     * @param array            $config    The config
     */
    private function configureOdm(ContainerBuilder $container, array $config): void
    {
        foreach ($config['mongodb']['listeners'] as $name => $listeners) {
            $this->configureDoctrineListeners($container, $listeners, $name, 'doctrine_mongodb.odm.event_subscriber');
            $this->documentManagers[$name] = $listeners;
        }
    }

    /**
     * Configure the doctrine listeners.
     *
     * @param ContainerBuilder $container The container service
     * @param array            $listeners The config listeners
     * @param string           $name      The doctrine manager name
     * @param string           $tagName   The tag name for doctrine event subscriber
     */
    private function configureDoctrineListeners(ContainerBuilder $container, array $listeners, string $name, string $tagName): void
    {
        foreach ($listeners as $ext => $enabled) {
            $listener = sprintf('klipper_doctrine_extensions_extra.listener.%s', $ext);

            if ($enabled && $container->hasDefinition($listener)) {
                $attributes = ['connection' => $name];

                if ('htmlable' === $ext) {
                    // the htmlable listener must be registered after others to work with them properly
                    $attributes['priority'] = -5;
                }

                $definition = $container->getDefinition($listener);
                $definition->addTag($tagName, $attributes);
            }
        }
    }

    /**
     * Configure the classes.
     *
     * @param ContainerBuilder $container The container service
     * @param array            $config    The config
     */
    private function configureClass(ContainerBuilder $container, array $config): void
    {
        foreach ($config['class'] as $listener => $class) {
            $container->setParameter(sprintf('klipper_doctrine_extensions_extra.listener.%s.class', $listener), $class);
        }
    }

    /**
     * Configure the request query services.
     *
     * @param ContainerBuilder $container The container service
     */
    private function configureRequestQuery(ContainerBuilder $container): void
    {
        if (!interface_exists(ObjectMetadataInterface::class)
                || !class_exists(OrderByWalker::class)
                || !class_exists(RequestStack::class)) {
            $container->removeDefinition('klipper_doctrine_extensions_extra.request_query.sortable');
        }

        if (!interface_exists(ObjectMetadataInterface::class)
                || !class_exists(RequestStack::class)) {
            $container->removeDefinition('klipper_doctrine_extensions_extra.request_query.searchable');
            $container->removeDefinition('klipper_doctrine_extensions_extra.request_query.filterable');
            $container->removeDefinition('klipper_doctrine_extensions_extra.request_query.filterable.parser');
            $container->removeDefinition('klipper_doctrine_extensions_extra.request_query.filterable.expression_language');
        }
    }
}
