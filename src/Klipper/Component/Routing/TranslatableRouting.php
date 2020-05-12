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

use Klipper\Component\HttpFoundation\Util\RequestUtil;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

/**
 * Translatable routing.
 *
 * @author François Pluchino <francois.pluchino@klipper.dev>
 */
class TranslatableRouting extends Routing implements TranslatableRoutingInterface
{
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
    public function getLangPath(string $name, array $parameters = [], bool $relative = false): string
    {
        return $this->getPath($name, $this->getLangParameters($parameters), $relative);
    }

    /**
     * {@inheritdoc}
     */
    public function getLangUrl(string $name, array $parameters = [], bool $schemeRelative = false): string
    {
        return $this->getUrl($name, $this->getLangParameters($parameters), $schemeRelative);
    }

    /**
     * Add the request query parameter for language.
     */
    protected function getLangParameters(array $parameters = []): array
    {
        $request = $this->requestStack->getCurrentRequest();

        return $request ? RequestUtil::getLangParameters($request, $parameters) : [];
    }
}
