<?php

/*
 * This file is part of the Klipper package.
 *
 * (c) François Pluchino <francois.pluchino@klipper.dev>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Klipper\Component\Importer\Pipeline;

use Psr\Log\LoggerInterface;

/**
 * @author François Pluchino <francois.pluchino@klipper.dev>
 */
abstract class AbstractPipeline implements PipelineInterface, LoggablePipelineInterface
{
    protected ?LoggerInterface $logger;

    public function __construct(?LoggerInterface $logger = null)
    {
        $this->logger = $logger;
    }

    public function getLogger(): ?LoggerInterface
    {
        return $this->logger;
    }
}
