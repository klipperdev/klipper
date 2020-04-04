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

use Klipper\Component\HttpFoundation\Util\RequestUtil;
use Symfony\Component\HttpFoundation\RequestStack;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;

/**
 * @author François Pluchino <francois.pluchino@klipper.dev>
 */
class RequestExtension extends AbstractExtension
{
    /**
     * @var RequestStack
     */
    protected $requestStack;

    /**
     * Constructor.
     *
     * @param RequestStack $requestStack The request stack
     */
    public function __construct(RequestStack $requestStack)
    {
        $this->requestStack = $requestStack;
    }

    /**
     * {@inheritdoc}
     */
    public function getFunctions(): array
    {
        return [
            new TwigFunction('request_uri', [$this, 'getRequestUri']),
            new TwigFunction('request_uri_for_path', [$this, 'getRequestUriForPath']),
            new TwigFunction('fake_host', [$this, 'fakeHost']),
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function getFilters(): array
    {
        return [
            new TwigFilter('fake_host', [$this, 'fakeHost']),
        ];
    }

    /**
     * Get the current request URI.
     */
    public function getRequestUri(): ?string
    {
        $request = $this->requestStack->getCurrentRequest();

        return $request ? RequestUtil::restoreFakeHost($request->getUri()) : null;
    }

    /**
     * Get the current request URI.
     *
     * @param string $path A path to use instead of the current one
     *
     * @return null|string
     */
    public function getRequestUriForPath(string $path): string
    {
        $request = $this->requestStack->getCurrentRequest();

        return $request ? RequestUtil::restoreFakeHost($request->getUriForPath($path), false) : null;
    }

    /**
     * Fake the host.
     *
     * @param string $url      The url
     * @param bool   $keepHost Check if the host must be added in the path
     */
    public function fakeHost(string $url, bool $keepHost = true): string
    {
        return RequestUtil::restoreFakeHost($url, $keepHost);
    }
}
