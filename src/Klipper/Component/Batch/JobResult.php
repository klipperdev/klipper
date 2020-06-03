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

use Symfony\Component\Validator\ConstraintViolationList;
use Symfony\Component\Validator\ConstraintViolationListInterface;

/**
 * The job result of batch.
 *
 * @author François Pluchino <francois.pluchino@klipper.dev>
 */
class JobResult implements JobResultInterface
{
    protected ConstraintViolationListInterface $constraintViolationList;

    protected ?\Throwable $throwable;

    protected bool $retryable = true;

    public function __construct(?\Throwable $exception = null)
    {
        $this->constraintViolationList = new ConstraintViolationList();
        $this->throwable = $exception;
    }

    public function isValid(): bool
    {
        return null === $this->throwable && 0 === $this->constraintViolationList->count();
    }

    public function setRetryable(bool $retryable)
    {
        $this->retryable = $retryable;

        return $this;
    }

    public function isRetryable(): bool
    {
        return $this->retryable;
    }

    public function getConstraintViolationList(): ConstraintViolationListInterface
    {
        return $this->constraintViolationList;
    }

    public function setThrowable(?\Throwable $throwable)
    {
        $this->throwable = $throwable;

        return $this;
    }

    public function getThrowable(): ?\Throwable
    {
        return $this->throwable;
    }
}
