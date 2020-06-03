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

use Symfony\Component\ErrorHandler\Exception\FlattenException;
use Symfony\Component\HttpFoundation\Response;

/**
 * Exception message manager.
 *
 * @author François Pluchino <francois.pluchino@klipper.dev>
 */
class ExceptionMessageManager
{
    private ExceptionTranslatorInterface $exceptionTranslator;

    private array $messages;

    private array $codes;

    /**
     * @param ExceptionTranslatorInterface $exceptionTranslator The exception translator
     * @param array                        $messages            The message map
     * @param array                        $codes               The status code map
     */
    public function __construct(
        ExceptionTranslatorInterface $exceptionTranslator,
        array $messages,
        array $codes
    ) {
        $this->exceptionTranslator = $exceptionTranslator;
        $this->messages = $messages;
        $this->codes = $codes;
    }

    /**
     * Get the exception message.
     *
     * @param FlattenException|\Throwable $exception The exception
     * @param string                      $message   The existing message
     */
    public function getMessage($exception, string $message): string
    {
        $showMessage = $this->resolve($exception);

        if ($showMessage) {
            return \is_string($showMessage) ? $this->exceptionTranslator->transMessage($showMessage) : $exception->getMessage();
        }

        return $this->exceptionTranslator->trans($message);
    }

    /**
     * Resolves the value corresponding to an exception object.
     *
     * @param FlattenException|\Throwable $exception The exception
     *
     * @return false|mixed Value found or false is not found
     */
    public function resolve($exception)
    {
        return $this->doResolveClassMessage($this->getClassName($exception));
    }

    /**
     * Get the status code for exception.
     *
     * @param FlattenException|\Throwable $exception The exception
     */
    public function getStatusCode($exception): int
    {
        $exceptionClass = $this->getClassName($exception);

        foreach ($this->codes as $class => $code) {
            if (is_a($exceptionClass, $class, true)) {
                return $code;
            }
        }

        return Response::HTTP_INTERNAL_SERVER_ERROR;
    }

    /**
     * Resolves the value corresponding to an exception class.
     *
     * @param string $class The class name
     *
     * @return false|mixed if not found
     */
    private function doResolveClassMessage(string $class)
    {
        foreach ($this->messages as $mapClass => $value) {
            if (false !== $value && is_a($class, $mapClass, true)) {
                return $value;
            }
        }

        return false;
    }

    /**
     * Get the exception class name.
     *
     * @param FlattenException|\Throwable $exception The exception
     */
    private function getClassName($exception): string
    {
        return $exception instanceof FlattenException
            ? $exception->getClass()
            : \get_class($exception);
    }
}
