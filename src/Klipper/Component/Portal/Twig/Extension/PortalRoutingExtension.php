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

use Klipper\Component\Portal\Routing\PortalRoutingInterface;
use Klipper\Component\Routing\Twig\Extension\Traits\UrlGenerationTrait;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

/**
 * @author François Pluchino <francois.pluchino@klipper.dev>
 */
class PortalRoutingExtension extends AbstractExtension
{
    use UrlGenerationTrait;

    private PortalRoutingInterface $portalRouting;

    public function __construct(PortalRoutingInterface $portalRouting)
    {
        $this->portalRouting = $portalRouting;
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('portal_url', [$this, 'getPortalUrl'], ['is_safe_callback' => [$this, 'isUrlGenerationSafe']]),
            new TwigFunction('portal_path', [$this, 'getPortalPath'], ['is_safe_callback' => [$this, 'isUrlGenerationSafe']]),
        ];
    }

    public function getPortalUrl(string $name, array $parameters = [], bool $schemeRelative = false): string
    {
        return $this->portalRouting->getPortalUrl($name, $parameters, $schemeRelative);
    }

    public function getPortalPath(string $name, array $parameters = [], bool $relative = false): string
    {
        return $this->portalRouting->getPortalPath($name, $parameters, $relative);
    }
}
