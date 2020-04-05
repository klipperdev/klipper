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

use JMS\Serializer\Visitor\SerializationVisitorInterface;
use Klipper\Component\Content\Serializer\UrlGenerator;

/**
 * @author François Pluchino <francois.pluchino@klipper.dev>
 */
class UrlGeneratorHandler
{
    /**
     * @var UrlGenerator
     */
    protected $urlGenerator;

    /**
     * Constructor.
     *
     * @param UrlGenerator $urlGenerator The serializer url generator
     */
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
     */
    public function generateUrl(SerializationVisitorInterface $visitor, $data, array $type): ?string
    {
        $object = $type['ci_url_gen_object'] ?? $data;
        $url = $this->urlGenerator->generate($type['name'], $type['params'], $object);

        return $visitor->visitString($url, $type);
    }
}
