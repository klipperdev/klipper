<?php

/*
 * This file is part of the Klipper package.
 *
 * (c) François Pluchino <francois.pluchino@klipper.dev>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Klipper\Component\Translation;

use Klipper\Component\Resource\Domain\DomainUtil;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * Exception translator.
 *
 * @author François Pluchino <francois.pluchino@klipper.dev>
 */
class ExceptionTranslator implements ExceptionTranslatorInterface
{
    protected TranslatorInterface $translator;

    protected bool $debug;

    /**
     * @param TranslatorInterface $translator The translator
     * @param bool                $debug      The debug mode
     */
    public function __construct(TranslatorInterface $translator, bool $debug = false)
    {
        $this->translator = $translator;
        $this->debug = $debug;
    }

    public function trans(string $message): string
    {
        if (\in_array($message, $this->getAvailableMessage(), true)) {
            $id = str_replace([' ', '-', '\''], '_', strtolower($message));
            $id = str_replace(['(', ')'], '', $id);
            $message = $this->translator->trans($id, [], 'exceptions');
        }

        return $message;
    }

    public function transMessage(string $message, array $params = []): string
    {
        return $this->translator->trans($message, $params, 'exceptions');
    }

    public function transDomainThrowable(\Throwable $throwable): string
    {
        return DomainUtil::getThrowableMessage($this->translator, $throwable, $this->debug);
    }

    protected function getAvailableMessage(): array
    {
        return [
            Response::$statusTexts[400],
            Response::$statusTexts[401],
            Response::$statusTexts[403],
            Response::$statusTexts[404],
            Response::$statusTexts[415],
        ];
    }
}
