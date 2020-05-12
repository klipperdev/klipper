<?php

/*
 * This file is part of the Klipper package.
 *
 * (c) François Pluchino <francois.pluchino@klipper.dev>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Klipper\Component\Routing;

use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

/**
 * Translatable routing.
 *
 * @author François Pluchino <francois.pluchino@klipper.dev>
 */
class Routing implements RoutingInterface
{
    /**
     * @var UrlGeneratorInterface
     */
    protected $generator;

    /**
     * @var RequestStack
     */
    protected $requestStack;

    /**
     * Constructor.
     *
     * @param UrlGeneratorInterface $generator    The url generator
     * @param RequestStack          $requestStack The request stack
     */
    public function __construct(UrlGeneratorInterface $generator, RequestStack $requestStack)
    {
        $this->generator = $generator;
        $this->requestStack = $requestStack;
    }

    /**
     * {@inheritdoc}
     */
    public function getUrlGenerator(): UrlGeneratorInterface
    {
        return $this->generator;
    }

    /**
     * {@inheritdoc}
     */
    public function getPath(string $name, array $parameters = [], bool $relative = false): string
    {
        return $this->generator->generate($name, $parameters, $relative ? UrlGeneratorInterface::RELATIVE_PATH : UrlGeneratorInterface::ABSOLUTE_PATH);
    }

    /**
     * {@inheritdoc}
     */
    public function getUrl(string $name, array $parameters = [], bool $schemeRelative = false): string
    {
        return $this->generator->generate($name, $parameters, $schemeRelative ? UrlGeneratorInterface::NETWORK_PATH : UrlGeneratorInterface::ABSOLUTE_URL);
    }
}
