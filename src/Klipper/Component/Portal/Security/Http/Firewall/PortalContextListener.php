<?php

/*
 * This file is part of the Klipper package.
 *
 * (c) François Pluchino <francois.pluchino@klipper.dev>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Klipper\Component\Portal\Security\Http\Firewall;

use Klipper\Component\Portal\PortalContextHelper;
use Symfony\Component\HttpKernel\Event\RequestEvent;

/**
 * Inject the portal defined in request path into the portal context.
 *
 * @author François Pluchino <francois.pluchino@klipper.dev>
 */
class PortalContextListener
{
    protected PortalContextHelper $helper;

    protected array $config;

    /**
     * @param PortalContextHelper $helper The helper of portal context
     * @param array               $config The config defined in firewall
     */
    public function __construct(PortalContextHelper $helper, array $config)
    {
        $this->helper = $helper;
        $this->config = $config;
    }

    public function __invoke(RequestEvent $event): void
    {
        $this->helper->setRouteParameterName($this->config['route_parameter_name']);
        $this->helper->injectContext($event->getRequest());
    }
}
