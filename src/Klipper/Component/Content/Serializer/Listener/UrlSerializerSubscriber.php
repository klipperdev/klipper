<?php

/*
 * This file is part of the Klipper package.
 *
 * (c) François Pluchino <francois.pluchino@klipper.dev>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Klipper\Component\Content\Serializer\Listener;

use JMS\Serializer\EventDispatcher\Events;
use JMS\Serializer\EventDispatcher\EventSubscriberInterface;
use JMS\Serializer\EventDispatcher\ObjectEvent;
use JMS\Serializer\Metadata\PropertyMetadata;
use Klipper\Component\Content\Serializer\UrlGenerator;
use Klipper\Component\DoctrineExtra\Util\ClassUtils;

/**
 * @author François Pluchino <francois.pluchino@klipper.dev>
 */
class UrlSerializerSubscriber implements EventSubscriberInterface
{
    public static function getSubscribedEvents(): array
    {
        return [
            [
                'event' => Events::PRE_SERIALIZE,
                'format' => 'json',
                'method' => 'onPreSerialize',
            ],
            [
                'event' => Events::POST_SERIALIZE,
                'format' => 'json',
                'method' => 'onPostSerialize',
            ],
        ];
    }

    /**
     * Replace url generator aliases by her real classname and inject object in property meta.
     *
     * @throws
     */
    public function onPreSerialize(ObjectEvent $event): void
    {
        $this->replaceClassAliases($event);
        $types = UrlGenerator::TYPES;

        if (!\is_object($event->getObject())) {
            return;
        }

        /** @var object $object */
        $object = $event->getObject();

        try {
            $classMeta = $event->getContext()->getMetadataFactory()->getMetadataForClass(ClassUtils::getClass($object));
            $classTypes = array_values($types);

            if (null === $classMeta) {
                return;
            }

            /** @var PropertyMetadata $propertyMeta */
            foreach ($classMeta->propertyMetadata as $propertyMeta) {
                $type = $propertyMeta->type;

                if (isset($type['name'], $types[$type['name']])) {
                    $propertyMeta->type['name'] = $types[$type['name']];
                }

                if (null !== $type && \in_array($propertyMeta->type['name'], $classTypes, true)) {
                    $propertyMeta->type['ci_url_gen_object'] = $object;
                }
            }
        } catch (\Throwable $e) {
            // do nothing
        }
    }

    /**
     * Clean the property metadatas.
     */
    public function onPostSerialize(ObjectEvent $event): void
    {
        if (!\is_object($event->getObject())) {
            return;
        }

        $object = $event->getObject();

        try {
            $classMeta = $event->getContext()->getMetadataFactory()->getMetadataForClass(ClassUtils::getClass($object));

            if (null === $classMeta) {
                return;
            }

            /** @var PropertyMetadata $propertyMeta */
            foreach ($classMeta->propertyMetadata as $propertyMeta) {
                if (isset($propertyMeta->type['ci_url_gen_object'])) {
                    unset($propertyMeta->type['ci_url_gen_object']);
                }
            }
        } catch (\Throwable $e) {
            // do nothing
        }
    }

    /**
     * @throws
     */
    private function replaceClassAliases(ObjectEvent $event): void
    {
        $eventType = $event->getType();
        $types = UrlGenerator::TYPES;

        if (isset($eventType['name'], $types[$eventType['name']])) {
            $eventType['name'] = $types[$eventType['name']];
            $ref = new \ReflectionClass($event);
            $refProp = $ref->getProperty('type');
            $refProp->setAccessible(true);
            $refProp->setValue($event, $eventType);
        }
    }
}
