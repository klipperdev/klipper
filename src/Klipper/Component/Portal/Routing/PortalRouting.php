<?php

/*
 * This file is part of the Klipper package.
 *
 * (c) François Pluchino <francois.pluchino@klipper.dev>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Klipper\Component\Portal\Routing;

use Klipper\Component\Portal\PortalContextInterface;
use Klipper\Component\Routing\TranslatableRouting;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Routing\RouterInterface;

/**
 * @author François Pluchino <francois.pluchino@klipper.dev>
 */
class PortalRouting extends TranslatableRouting implements PortalRoutingInterface
{
    private RouterInterface $router;

    private PortalContextInterface $context;

    /**
     * @param RouterInterface        $router       The router
     * @param RequestStack           $requestStack The request stack
     * @param PortalContextInterface $context      The portal context
     */
    public function __construct(
        RouterInterface $router,
        RequestStack $requestStack,
        PortalContextInterface $context
    ) {
        parent::__construct($router, $requestStack);

        $this->router = $router;
        $this->context = $context;
    }

    public function getPortalPath(string $name, array $parameters = [], bool $relative = false): string
    {
        $parameters = $this->getPortalParameters($name, $parameters);

        return $this->getPath($name, $parameters, $relative);
    }

    public function getLangPortalPath(string $name, array $parameters = [], bool $relative = false): string
    {
        return $this->getPortalPath($name, $this->getLangParameters($parameters), $relative);
    }

    public function getPortalUrl(string $name, array $parameters = [], bool $schemeRelative = false): string
    {
        $parameters = $this->getPortalParameters($name, $parameters);

        return $this->getUrl($name, $parameters, $schemeRelative);
    }

    public function getLangPortalUrl(string $name, array $parameters = [], bool $schemeRelative = false): string
    {
        return $this->getPortalUrl($name, $this->getLangParameters($parameters), $schemeRelative);
    }

    public function getPortalParameters(string $name, array $parameters = []): array
    {
        $route = $this->router->getRouteCollection()->get($name);
        $mergedParams = $parameters;

        if (null !== $route && $route->hasDefault('_portal')
            && !isset($mergedParams[$route->getDefault('_portal')])
        ) {
            $portalParamName = $route->getDefault('_portal');

            if ($this->context->isPortal() && null !== $this->context->getCurrentPortal()) {
                $mergedParams[$portalParamName] = $this->context->getCurrentPortal()->getPortalName();
            } else {
                $mergedParams[$portalParamName] = '_';
            }
        }

        return $mergedParams;
    }
}
