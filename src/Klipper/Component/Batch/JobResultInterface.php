<?php

/*
 * This file is part of the Klipper package.
 *
 * (c) François Pluchino <francois.pluchino@klipper.dev>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Klipper\Component\Batch;

use Symfony\Component\Validator\ConstraintViolationListInterface;

/**
 * @author François Pluchino <francois.pluchino@klipper.dev>
 */
interface JobResultInterface
{
    /**
     * Check if the job is valid.
     */
    public function isValid(): bool;

    /**
     * Define if the job is retryable.
     *
     * @param bool $retryable The retryable value
     *
     * @return static
     */
    public function setRetryable(bool $retryable);

    /**
     * Check if the job is retryable.
     */
    public function isRetryable(): bool;

    /**
     * Get the constraint violation list.
     */
    public function getConstraintViolationList(): ConstraintViolationListInterface;

    /**
     * Get the exception.
     *
     * @param null|\Exception $exception The exception
     *
     * @return static
     */
    public function setException(?\Exception $exception);

    /**
     * Get the exception.
     */
    public function getException(): ?\Exception;
}
