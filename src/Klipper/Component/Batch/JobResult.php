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
    /**
     * @var ConstraintViolationListInterface
     */
    protected $constraintViolationList;

    /**
     * @var null|\Exception
     */
    protected $exception;

    /**
     * @var bool
     */
    protected $retryable = true;

    /**
     * Constructor.
     *
     * @param null|\Exception $exception The exception
     */
    public function __construct(?\Exception $exception = null)
    {
        $this->constraintViolationList = new ConstraintViolationList();
        $this->exception = $exception;
    }

    /**
     * {@inheritdoc}
     */
    public function isValid(): bool
    {
        return null === $this->exception && 0 === $this->constraintViolationList->count();
    }

    /**
     * {@inheritdoc}
     */
    public function setRetryable(bool $retryable)
    {
        $this->retryable = $retryable;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function isRetryable(): bool
    {
        return $this->retryable;
    }

    /**
     * {@inheritdoc}
     */
    public function getConstraintViolationList(): ConstraintViolationListInterface
    {
        return $this->constraintViolationList;
    }

    /**
     * {@inheritdoc}
     */
    public function setException(?\Exception $exception)
    {
        $this->exception = $exception;

        return $this;
    }

    public function getException(): ?\Exception
    {
        return $this->exception;
    }
}
