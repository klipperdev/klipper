<?php

/*
 * This file is part of the Klipper package.
 *
 * (c) François Pluchino <francois.pluchino@klipper.dev>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Klipper\Component\Translation\Twig\Extension;

use Klipper\Component\Translation\ExceptionMessageManager;
use Symfony\Component\Debug\Exception\FlattenException;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

/**
 * Exception resolve value for twig template.
 *
 * @author François Pluchino <francois.pluchino@klipper.dev>
 */
class ExceptionMessageExtension extends AbstractExtension
{
    /**
     * @var ExceptionMessageManager
     */
    protected $exceptionMessageManager;

    /**
     * Constructor.
     *
     * @param ExceptionMessageManager $exceptionMessageManager The exception message manager
     */
    public function __construct(ExceptionMessageManager $exceptionMessageManager)
    {
        $this->exceptionMessageManager = $exceptionMessageManager;
    }

    /**
     * {@inheritdoc}
     */
    public function getFunctions(): array
    {
        return [
            new TwigFunction('exception_value_map', [$this, 'resolveException']),
            new TwigFunction('exception_message', [$this, 'getExceptionMessage']),
        ];
    }

    /**
     * Get the exception message.
     *
     * @param FlattenException $exception The flatten exception
     * @param string           $message   The existing message
     */
    public function getExceptionMessage(FlattenException $exception, string $message): string
    {
        return $this->exceptionMessageManager->getMessage($exception, $message);
    }

    /**
     * Resolves the value corresponding to an exception object.
     *
     * @param FlattenException $exception The flatten exception
     *
     * @return false|mixed Value found or false is not found
     */
    public function resolveException(FlattenException $exception)
    {
        return $this->exceptionMessageManager->resolve($exception);
    }
}
