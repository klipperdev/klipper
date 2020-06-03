<?php

/*
 * This file is part of the Klipper package.
 *
 * (c) François Pluchino <francois.pluchino@klipper.dev>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Klipper\Component\Mailer\Mime;

use Symfony\Component\Mime\BodyRendererInterface;
use Symfony\Component\Mime\Message;

/**
 * @author François Pluchino <francois.pluchino@klipper.dev>
 */
class SandboxBodyRenderer implements BodyRendererInterface
{
    private BodyRendererInterface $renderer;

    private ?SandboxTemplaterInterface $sandboxTemplater;

    /**
     * @param BodyRendererInterface          $renderer         The body renderer
     * @param null|SandboxTemplaterInterface $sandboxTemplater The sandbox templater
     */
    public function __construct(BodyRendererInterface $renderer, ?SandboxTemplaterInterface $sandboxTemplater = null)
    {
        $this->renderer = $renderer;
        $this->sandboxTemplater = $sandboxTemplater;
    }

    public function render(Message $message): void
    {
        $isSandboxed = null;

        if (null !== $this->sandboxTemplater && $message instanceof SandboxInterface) {
            $isSandboxed = $this->sandboxTemplater->isSandboxed();
            $this->sandboxTemplater->enableSandbox();
        }

        $this->renderer->render($message);

        if (null !== $this->sandboxTemplater && false === $isSandboxed) {
            $this->sandboxTemplater->disableSandbox();
        }
    }
}
