<?php

/*
 * This file is part of the Klipper package.
 *
 * (c) François Pluchino <francois.pluchino@klipper.dev>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Klipper\Component\SmsSender\Transport;

/**
 * Result of the transport.
 *
 * @author François Pluchino <francois.pluchino@klipper.dev>
 */
class Result
{
    private string $transportClassName;

    /**
     * @var SuccessResult[]
     */
    private array $successes = [];

    /**
     * @var ErrorResult[]
     */
    private array $errors = [];

    /**
     * @param string $transportClassName The class name of the transport
     */
    public function __construct(string $transportClassName)
    {
        $this->transportClassName = $transportClassName;
    }

    /**
     * Get the class name of the transport.
     */
    public function getTransportClassName(): string
    {
        return $this->transportClassName;
    }

    /**
     * Add the result item.
     *
     * @param AbstractResultItem|ErrorResult|SuccessResult $result The result item
     */
    public function add(AbstractResultItem $result): void
    {
        if ($result instanceof SuccessResult) {
            $this->successes[] = $result;
        } elseif ($result instanceof ErrorResult) {
            $this->errors[] = $result;
        }
    }

    /**
     * Get the success items.
     *
     * @return SuccessResult[]
     */
    public function getSuccesses(): array
    {
        return $this->successes;
    }

    /**
     * Check if the result has errors.
     */
    public function hasErrors(): bool
    {
        return !empty($this->errors);
    }

    /**
     * Get the error items.
     *
     * @return ErrorResult[]
     */
    public function getErrors(): array
    {
        return $this->errors;
    }
}
