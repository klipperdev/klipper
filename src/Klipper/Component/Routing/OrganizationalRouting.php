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

use Klipper\Component\Security\Organizational\OrganizationalContextInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Routing\RouterInterface;

/**
 * Organizational routing.
 *
 * @author François Pluchino <francois.pluchino@klipper.dev>
 */
class OrganizationalRouting extends TranslatableRouting implements OrganizationalRoutingInterface
{
    /**
     * @var RouterInterface
     */
    private $router;

    /**
     * @var OrganizationalContextInterface
     */
    private $context;

    /**
     * Constructor.
     *
     * @param RouterInterface                $router       The router
     * @param RequestStack                   $requestStack The request stack
     * @param OrganizationalContextInterface $context      The organizational context
     */
    public function __construct(RouterInterface $router, RequestStack $requestStack, OrganizationalContextInterface $context)
    {
        parent::__construct($router, $requestStack);

        $this->router = $router;
        $this->context = $context;
    }

    /**
     * {@inheritdoc}
     */
    public function getOrgPath(string $name, array $parameters = [], bool $relative = false): string
    {
        $parameters = $this->getOrgParameters($name, $parameters);

        return $this->getPath($name, $parameters, $relative);
    }

    /**
     * {@inheritdoc}
     */
    public function getLangOrgPath(string $name, array $parameters = [], bool $relative = false): string
    {
        return $this->getOrgPath($name, $this->getLangParameters($parameters), $relative);
    }

    /**
     * {@inheritdoc}
     */
    public function getOrgUrl(string $name, array $parameters = [], bool $schemeRelative = false): string
    {
        $parameters = $this->getOrgParameters($name, $parameters);

        return $this->getUrl($name, $parameters, $schemeRelative);
    }

    /**
     * {@inheritdoc}
     */
    public function getLangOrgUrl(string $name, array $parameters = [], bool $schemeRelative = false): string
    {
        return $this->getOrgUrl($name, $this->getLangParameters($parameters), $schemeRelative);
    }

    /**
     * {@inheritdoc}
     */
    public function getOrgParameters(string $name, array $parameters = []): array
    {
        $route = $this->router->getRouteCollection()->get($name);

        if (null !== $route && $route->hasDefault('_organizational')
                && !isset($parameters[$route->getDefault('_organizational')])) {
            $parameters = array_merge($parameters, [
                'organization' => $this->context->isOrganization() && null !== $this->context->getCurrentOrganization()
                    ? $this->context->getCurrentOrganization()->getName()
                    : 'user',
            ]);
        }

        return $parameters;
    }
}
