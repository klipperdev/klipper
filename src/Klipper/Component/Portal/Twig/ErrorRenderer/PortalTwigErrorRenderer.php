<?php

/*
 * This file is part of the Klipper package.
 *
 * (c) François Pluchino <francois.pluchino@klipper.dev>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Klipper\Component\Portal\Twig\ErrorRenderer;

use Symfony\Bridge\Twig\ErrorRenderer\TwigErrorRenderer as SymfonyTwigErrorRenderer;
use Symfony\Component\ErrorHandler\ErrorRenderer\ErrorRendererInterface;
use Symfony\Component\ErrorHandler\ErrorRenderer\HtmlErrorRenderer;
use Symfony\Component\ErrorHandler\Exception\FlattenException;
use Symfony\Component\HttpFoundation\RequestStack;
use Twig\Environment;

/**
 * @author François Pluchino <francois.pluchino@klipper.dev>
 */
class PortalTwigErrorRenderer implements ErrorRendererInterface
{
    private SymfonyTwigErrorRenderer $twigErrorRenderer;

    private Environment $twig;

    private ?HtmlErrorRenderer $fallbackErrorRenderer;

    /**
     * @var bool|callable
     */
    private $debug;

    private string $templateBasePath;

    /**
     * @param bool|callable $debug
     */
    public function __construct(
        SymfonyTwigErrorRenderer $twigErrorRenderer,
        Environment $twig,
        ?HtmlErrorRenderer $fallbackErrorRenderer = null,
        $debug = false,
        string $templateBasePath = 'portal/'
    ) {
        $this->twigErrorRenderer = $twigErrorRenderer;
        $this->twig = $twig;
        $this->fallbackErrorRenderer = $fallbackErrorRenderer;
        $this->debug = $debug;
        $this->templateBasePath = $templateBasePath;
    }

    /**
     * @throws
     */
    public function render(\Throwable $exception): FlattenException
    {
        $portalException = $this->fallbackErrorRenderer->render($exception);
        $debug = \is_bool($this->debug) ? $this->debug : ($this->debug)($portalException);

        if ($debug || !$template = $this->findTemplate($portalException->getStatusCode())) {
            return $this->twigErrorRenderer->render($exception);
        }

        return $portalException->setAsString($this->twig->render($template, [
            'exception' => $portalException,
            'status_code' => $portalException->getStatusCode(),
            'status_text' => $portalException->getStatusText(),
        ]));
    }

    public static function isDebug(RequestStack $requestStack, bool $debug): \Closure
    {
        return SymfonyTwigErrorRenderer::isDebug($requestStack, $debug);
    }

    private function findTemplate(int $statusCode): ?string
    {
        $template = sprintf($this->templateBasePath.'exception/error%s.html.twig', $statusCode);

        if ($this->twig->getLoader()->exists($template)) {
            return $template;
        }

        $template = $this->templateBasePath.'exception/error.html.twig';

        if ($this->twig->getLoader()->exists($template)) {
            return $template;
        }

        return null;
    }
}
