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
    /**
     * @var TranslatorInterface
     */
    protected $translator;

    /**
     * @var bool
     */
    protected $debug;

    /**
     * Constructor.
     *
     * @param TranslatorInterface $translator The translator
     * @param bool                $debug      The debug mode
     */
    public function __construct(TranslatorInterface $translator, bool $debug = false)
    {
        $this->translator = $translator;
        $this->debug = $debug;
    }

    /**
     * {@inheritdoc}
     */
    public function trans(string $message): string
    {
        if (\in_array($message, $this->getAvailableMessage(), true)) {
            $id = str_replace([' ', '-', '\''], '_', strtolower($message));
            $id = str_replace(['(', ')'], '', $id);
            $message = $this->translator->trans($id, [], 'exceptions');
        }

        return $message;
    }

    /**
     * {@inheritdoc}
     */
    public function transMessage(string $message, array $params = []): string
    {
        return $this->translator->trans($message, $params, 'exceptions');
    }

    /**
     * {@inheritdoc}
     */
    public function transDomainException(\Exception $exception): string
    {
        return DomainUtil::getExceptionMessage($this->translator, $exception, $this->debug);
    }

    protected function getAvailableMessage(): array
    {
        return [
            Response::$statusTexts[400],
            Response::$statusTexts[401],
            Response::$statusTexts[403],
            Response::$statusTexts[404],
            Response::$statusTexts[415],
            Response::$statusTexts[422],
        ];
    }
}
