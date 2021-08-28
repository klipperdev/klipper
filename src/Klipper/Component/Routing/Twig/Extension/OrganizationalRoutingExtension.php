<?php

/*
 * This file is part of the Klipper package.
 *
 * (c) François Pluchino <francois.pluchino@klipper.dev>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Klipper\Component\Routing\Twig\Extension;

use Klipper\Component\Routing\OrganizationalRoutingInterface;
use Twig\TwigFunction;

/**
 * @author François Pluchino <francois.pluchino@klipper.dev>
 */
class OrganizationalRoutingExtension extends TranslatableRoutingExtension
{
    /**
     * Constructor.
     *
     * @param OrganizationalRoutingInterface $routing The organizational routing
     */
    public function __construct(OrganizationalRoutingInterface $routing)
    {
        parent::__construct($routing);
    }

    public function getFunctions(): array
    {
        return array_merge(parent::getFunctions(), [
            new TwigFunction('org_url', [$this->routing, 'getOrgUrl'], ['is_safe_callback' => [$this, 'isUrlGenerationSafe']]),
            new TwigFunction('org_path', [$this->routing, 'getOrgPath'], ['is_safe_callback' => [$this, 'isUrlGenerationSafe']]),
            new TwigFunction('lang_org_url', [$this->routing, 'getLangOrgUrl'], ['is_safe_callback' => [$this, 'isUrlGenerationSafe']]),
            new TwigFunction('lang_org_path', [$this->routing, 'getLangOrgPath'], ['is_safe_callback' => [$this, 'isUrlGenerationSafe']]),
        ]);
    }
}
