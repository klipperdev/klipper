<?php

/*
 * This file is part of the Klipper package.
 *
 * (c) François Pluchino <francois.pluchino@klipper.dev>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Klipper\Component\Config\Listener;

use Doctrine\Common\Annotations\Reader;
use Klipper\Component\Config\Annotation\AnnotationInterface;
use Klipper\Component\Config\Exception\LogicException;
use Klipper\Component\Config\Exception\UnexpectedValueException;
use Klipper\Component\DoctrineExtra\Util\ClassUtils;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ControllerEvent;
use Symfony\Component\HttpKernel\KernelEvents;

/**
 * @author François Pluchino <francois.pluchino@klipper.dev>
 */
class ControllerSubscriber implements EventSubscriberInterface
{
    /**
     * @var Reader
     */
    private $reader;

    /**
     * Constructor.
     *
     * @param Reader $reader The annotation reader
     */
    public function __construct(Reader $reader)
    {
        $this->reader = $reader;
    }

    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::CONTROLLER => 'onKernelController',
        ];
    }

    /**
     * Modifies the Request object to apply configuration information found in
     * controllers annotations.
     *
     * @param ControllerEvent $event The event
     *
     * @throws
     */
    public function onKernelController(ControllerEvent $event): void
    {
        $controller = $event->getController();

        if (!\is_array($controller) && method_exists($controller, '__invoke')) {
            $controller = [$controller, '__invoke'];
        }

        $request = $event->getRequest();
        $className = \get_class($controller[0]);
        $className = class_exists(ClassUtils::class) ? ClassUtils::getRealClass($className) : $className;
        $object = new \ReflectionClass($className);
        $method = $object->getMethod($controller[1]);

        $configurations = [];
        $classConfigurations = $this->getConfigurations($this->reader->getClassAnnotations($object));
        $methodConfigurations = $this->getConfigurations($this->reader->getMethodAnnotations($method));

        foreach (array_merge(array_keys($classConfigurations), array_keys($methodConfigurations)) as $key) {
            if (!\array_key_exists($key, $classConfigurations)) {
                $configurations[$key] = $methodConfigurations[$key];
            } elseif (!\array_key_exists($key, $methodConfigurations)) {
                $configurations[$key] = $classConfigurations[$key];
            } elseif (\is_array($classConfigurations[$key])) {
                if (!\is_array($methodConfigurations[$key])) {
                    throw new UnexpectedValueException('Configurations should both be an array or both not be an array');
                }

                $configurations[$key] = array_merge($classConfigurations[$key], $methodConfigurations[$key]);
            } else {
                // method configuration overrides class configuration
                $configurations[$key] = $methodConfigurations[$key];
            }
        }

        foreach ($configurations as $key => $attributes) {
            $request->attributes->set($key, $attributes);
        }
    }

    /**
     * Get the configurations.
     *
     * @param array $annotations all annotations
     *
     * @return AnnotationInterface[]
     */
    private function getConfigurations(array $annotations): array
    {
        $configurations = [];

        foreach ($annotations as $configuration) {
            if ($configuration instanceof AnnotationInterface && null !== $alias = $configuration->getAliasName()) {
                if ($configuration->allowArray()) {
                    $configurations['_'.$alias][] = $configuration;
                } elseif (!\array_key_exists('_'.$alias, $configurations)) {
                    $configurations['_'.$alias] = $configuration;
                } else {
                    throw new LogicException(sprintf('Multiple "%s" annotations are not allowed', $alias));
                }
            }
        }

        return $configurations;
    }
}
