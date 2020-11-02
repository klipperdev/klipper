<?php

/*
 * This file is part of the Klipper package.
 *
 * (c) François Pluchino <francois.pluchino@klipper.dev>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Klipper\Component\Content\Serializer\Handler;

use JMS\Serializer\SerializationContext;
use JMS\Serializer\Visitor\SerializationVisitorInterface;
use Klipper\Component\Content\Serializer\UrlGenerator;

/**
 * @author François Pluchino <francois.pluchino@klipper.dev>
 */
class UrlGeneratorHandler
{
    protected UrlGenerator $urlGenerator;

    public function __construct(UrlGenerator $urlGenerator)
    {
        $this->urlGenerator = $urlGenerator;
    }

    /**
     * Generate the url.
     *
     * @param SerializationVisitorInterface $visitor The serializer visitor
     * @param mixed                         $data    The data
     * @param array                         $type    The serializer type
     * @param SerializationContext          $context The serialization context
     */
    public function generateUrl(SerializationVisitorInterface $visitor, $data, array $type, SerializationContext $context): ?string
    {
        $object = $context->getObject();

        return \is_object($object)
            ? $visitor->visitString($this->urlGenerator->generate($type['name'], $type['params'], $object), $type)
            : null;
    }
}
