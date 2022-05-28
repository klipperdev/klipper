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
use JMS\Serializer\EventDispatcher\PreSerializeEvent;
use JMS\Serializer\Metadata\PropertyMetadata;
use Klipper\Component\Content\Serializer\UrlGenerator;
use Klipper\Component\DoctrineExtra\Util\ClassUtils;

/**
 * @author François Pluchino <francois.pluchino@klipper.dev>
 */
class UrlSerializerSubscriber implements EventSubscriberInterface
{
    private array $cache = [];

    public static function getSubscribedEvents(): array
    {
        return [
            [
                'event' => Events::PRE_SERIALIZE,
                'format' => 'json',
                'method' => 'onPreSerialize',
            ],
        ];
    }

    /**
     * Replace url generator aliases by her real classname and inject object in property meta.
     *
     * @throws
     */
    public function onPreSerialize(PreSerializeEvent $event): void
    {
        $object = $event->getObject();

        if (!\is_object($object)) {
            return;
        }

        $class = ClassUtils::getClass($object);

        if (!\in_array($class, $this->cache, true)) {
            $this->cache[] = $class;
            $classMeta = $event->getContext()->getMetadataFactory()->getMetadataForClass($class);

            if (null !== $classMeta) {
                /** @var PropertyMetadata $propertyMeta */
                foreach ($classMeta->propertyMetadata as $propertyMeta) {
                    if (null === $propertyMeta->type) {
                        continue;
                    }

                    if (isset($propertyMeta->type['name'], UrlGenerator::TYPES[$propertyMeta->type['name']])) {
                        $propertyMeta->type['name'] = UrlGenerator::TYPES[$propertyMeta->type['name']];
                    }
                }
            }
        }
    }
}
