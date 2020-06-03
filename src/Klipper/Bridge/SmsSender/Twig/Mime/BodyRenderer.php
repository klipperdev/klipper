<?php

/*
 * This file is part of the Klipper package.
 *
 * (c) François Pluchino <francois.pluchino@klipper.dev>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Klipper\Bridge\SmsSender\Twig\Mime;

use Symfony\Component\Mime\BodyRendererInterface;
use Symfony\Component\Mime\Message;
use Twig\Environment;

/**
 * SMS body renderer.
 *
 * @author François Pluchino <francois.pluchino@klipper.dev>
 */
class BodyRenderer implements BodyRendererInterface
{
    private Environment $twig;

    private array $context;

    /**
     * @param array $context The default twig context
     */
    public function __construct(Environment $twig, array $context = [])
    {
        $this->twig = $twig;
        $this->context = $context;
    }

    /**
     * @throws
     */
    public function render(Message $message): void
    {
        if (!$message instanceof TemplatedSms) {
            return;
        }

        $vars = array_merge($this->context, $message->getContext(), [
            'sms' => new WrappedTemplatedSms($message),
        ]);

        if ($template = $message->getTemplate()) {
            $message->text(trim(strip_tags($this->twig->render($template, $vars))));
        }
    }
}
