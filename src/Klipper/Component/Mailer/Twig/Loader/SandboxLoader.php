<?php

/*
 * This file is part of the Klipper package.
 *
 * (c) François Pluchino <francois.pluchino@klipper.dev>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Klipper\Component\Mailer\Twig\Loader;

use Klipper\Component\Mailer\Mime\SandboxTemplaterInterface;
use Twig\Error\LoaderError;
use Twig\Loader\LoaderInterface;
use Twig\Source;

/**
 * @author François Pluchino <francois.pluchino@klipper.dev>
 */
class SandboxLoader implements LoaderInterface
{
    /**
     * @var string[]
     */
    private array $availableNamespaces;

    private SandboxTemplaterInterface $sandboxTemplater;

    /**
     * @param SandboxTemplaterInterface $sandboxTemplater    The sandbox templater
     * @param string[]                  $availableNamespaces The available namespaces
     */
    public function __construct(
        SandboxTemplaterInterface $sandboxTemplater,
        array $availableNamespaces = []
    ) {
        $this->sandboxTemplater = $sandboxTemplater;
        $this->availableNamespaces = $availableNamespaces;
    }

    /**
     * @param mixed $name
     */
    public function getSourceContext($name): Source
    {
        return new Source('', $name);
    }

    /**
     * @param mixed $name
     */
    public function getCacheKey($name): string
    {
        return $name;
    }

    /**
     * @param mixed $name
     * @param mixed $time
     */
    public function isFresh($name, $time): bool
    {
        return false;
    }

    /**
     * @param mixed $name
     *
     * @throws
     */
    public function exists($name): bool
    {
        if ($this->sandboxTemplater->isSandboxed()
                && !\in_array($this->parseNamespace($name), $this->availableNamespaces, true)) {
            throw new LoaderError(sprintf('Unable to find template "%s".', $name));
        }

        return false;
    }

    /**
     * Retrieve the namespace of template name.
     *
     * @param string      $name    The template name
     * @param null|string $default The default namespace
     */
    private function parseNamespace(string $name, ?string $default = null): ?string
    {
        if (0 === strpos($name, '@')) {
            if (false === $pos = strpos($name, '/')) {
                $pos = \strlen($name);
            }

            return substr($name, 1, $pos - 1);
        }

        return $default;
    }
}
