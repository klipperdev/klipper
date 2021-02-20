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
use Twig\Extension\AbstractExtension;
use Twig\Node\Expression\ArrayExpression;
use Twig\Node\Expression\ConstantExpression;
use Twig\Node\Node;
use Twig\TwigFunction;

/**
 * @author François Pluchino <francois.pluchino@klipper.dev>
 */
class PortalRoutingExtension extends AbstractExtension
{
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

    /**
     * Determines at compile time whether the generated URL will be safe and thus
     * saving the unneeded automatic escaping for performance reasons.
     *
     * The URL generation process percent encodes non-alphanumeric characters. So there is no risk
     * that malicious/invalid characters are part of the URL. The only character within an URL that
     * must be escaped in html is the ampersand ("&") which separates query params. So we cannot mark
     * the URL generation as always safe, but only when we are sure there won't be multiple query
     * params. This is the case when there are none or only one constant parameter given.
     * E.g. we know beforehand this will be safe:
     * - portal_path('route')
     * - portal_path('route', {'param': 'value'})
     * But the following may not:
     * - portal_path('route', var)
     * - portal_path('route', {'param': ['val1', 'val2'] }) // a sub-array
     * - portal_path('route', {'param1': 'value1', 'param2': 'value2'})
     * If param1 and param2 reference placeholder in the route, it would still be safe. But we don't know.
     *
     * @param Node $argsNode The arguments of the path/url function
     *
     * @return array An array with the contexts the URL is safe
     */
    public function isUrlGenerationSafe(Node $argsNode): array
    {
        // support named arguments
        $paramsNode = $argsNode->hasNode('parameters') ? $argsNode->getNode('parameters') : (
            $argsNode->hasNode(1) ? $argsNode->getNode(1) : null
        );

        if (null === $paramsNode || $paramsNode instanceof ArrayExpression && \count($paramsNode) <= 2
            && (!$paramsNode->hasNode(1) || $paramsNode->getNode(1) instanceof ConstantExpression)
        ) {
            return ['html'];
        }

        return [];
    }
}
