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
use JMS\Serializer\Metadata\StaticPropertyMetadata;
use Klipper\Component\Content\Serializer\UrlGenerator;

/**
 * @author François Pluchino <francois.pluchino@klipper.dev>
 */
class UrlSerializerSubscriber implements EventSubscriberInterface
{
    protected UrlGenerator $urlGenerator;

    public function __construct(UrlGenerator $urlGenerator)
    {
        $this->urlGenerator = $urlGenerator;
    }

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
    public function onPreSerialize(ObjectEvent $event): void
    {
        $object = $event->getObject();

        if (!\is_object($object)) {
            return;
        }

        $classMeta = $event->getContext()->getMetadataFactory()->getMetadataForClass(\get_class($object));

        if (null !== $classMeta) {
            /** @var PropertyMetadata $propertyMeta */
            foreach ($classMeta->propertyMetadata as $i => $propertyMeta) {
                if (null === $propertyMeta->type) {
                    continue;
                }

                if (\in_array($propertyMeta->type['name'], array_keys(UrlGenerator::TYPES), true)) {
                    $propertyMeta->type['name'] = UrlGenerator::TYPES[$propertyMeta->type['name']];
                }

                if (\in_array($propertyMeta->type['name'], array_values(UrlGenerator::TYPES), true)) {
                    $classMeta->propertyMetadata[$i] = $staticPropMeta = new StaticPropertyMetadata(
                        $propertyMeta->class,
                        $propertyMeta->serializedName,
                        $this->urlGenerator->generate(
                            $propertyMeta->type['name'],
                            $propertyMeta->type['params'],
                            $object
                        ),
                    );
                    $staticPropMeta->sinceVersion = $propertyMeta->sinceVersion;
                    $staticPropMeta->untilVersion = $propertyMeta->untilVersion;
                    $staticPropMeta->groups = $propertyMeta->groups;
                    $staticPropMeta->inline = $propertyMeta->inline;
                    $staticPropMeta->skipWhenEmpty = $propertyMeta->skipWhenEmpty;
                    $staticPropMeta->excludeIf = $propertyMeta->excludeIf;
                }
            }
        }
    }
}
