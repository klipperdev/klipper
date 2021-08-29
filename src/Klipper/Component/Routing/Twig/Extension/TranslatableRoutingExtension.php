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

use Klipper\Component\Routing\TranslatableRoutingInterface;
use Klipper\Component\Routing\Twig\Extension\Traits\UrlGenerationTrait;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

/**
 * @author François Pluchino <francois.pluchino@klipper.dev>
 */
class TranslatableRoutingExtension extends AbstractExtension
{
    use UrlGenerationTrait;

    protected TranslatableRoutingInterface $routing;

    public function __construct(TranslatableRoutingInterface $routing)
    {
        $this->routing = $routing;
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('lang_url', [$this->routing, 'getLangUrl'], ['is_safe_callback' => [$this, 'isUrlGenerationSafe']]),
            new TwigFunction('lang_path', [$this->routing, 'getLangPath'], ['is_safe_callback' => [$this, 'isUrlGenerationSafe']]),
        ];
    }
}
