<?php

/*
 * This file is part of the Klipper package.
 *
 * (c) François Pluchino <francois.pluchino@klipper.dev>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Klipper\Component\Portal\Twig\Extension;

use Klipper\Component\Portal\Model\PortalInterface;
use Klipper\Component\Portal\Model\PortalUserInterface;
use Klipper\Component\Portal\PortalContextInterface;
use Klipper\Component\Portal\PortalManagerInterface;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

/**
 * @author François Pluchino <francois.pluchino@klipper.dev>
 */
class PortalContextExtension extends AbstractExtension
{
    private ?PortalContextInterface $portalContext;

    private ?PortalManagerInterface $portalManager;

    public function __construct(
        ?PortalContextInterface $portalContext,
        ?PortalManagerInterface $portalManager
    ) {
        $this->portalContext = $portalContext;
        $this->portalManager = $portalManager;
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('current_portal', [$this, 'getCurrentPortal']),
            new TwigFunction('current_portal_user', [$this, 'getCurrentPortalUser']),
            new TwigFunction('current_portal_name', [$this, 'getCurrentPortalName']),
            new TwigFunction('current_portal_unique_name', [$this, 'getCurrentPortalUniqueName']),
            new TwigFunction('available_portals', [$this, 'getAvailablePortals']),
        ];
    }

    public function getCurrentPortal(): ?PortalInterface
    {
        return null !== $this->portalContext ? $this->portalContext->getCurrentPortal() : null;
    }

    public function getCurrentPortalUser(): ?PortalUserInterface
    {
        return null !== $this->portalContext ? $this->portalContext->getCurrentPortalUser() : null;
    }

    public function getCurrentPortalName(): ?string
    {
        if (null === $this->portalContext) {
            return null;
        }

        $portal = $this->portalContext->getCurrentPortal();

        if (\is_object($portal) && method_exists($portal, 'getName')) {
            return $portal->getName();
        }

        return null !== $portal ? $portal->getPortalName() : null;
    }

    public function getCurrentPortalUniqueName(): ?string
    {
        if (null === $this->portalContext) {
            return null;
        }

        $portal = $this->portalContext->getCurrentPortal();

        return null !== $portal ? $portal->getPortalName() : null;
    }

    public function getAvailablePortals(): array
    {
        return null !== $this->portalManager ? $this->portalManager->getAvailablePortals() : [];
    }
}
