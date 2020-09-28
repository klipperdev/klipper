<?php

/*
 * This file is part of the Klipper package.
 *
 * (c) François Pluchino <francois.pluchino@klipper.dev>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Klipper\Component\DoctrineChoice\DataLoader;

use Symfony\Component\Config\Definition\ConfigurationInterface;
use Symfony\Component\Config\Definition\Processor;

/**
 * @author François Pluchino <francois.pluchino@klipper.dev>
 */
class ChoiceProcessor
{
    protected Processor $processor;

    /**
     * @param null|Processor $processor The config processor
     */
    public function __construct(?Processor $processor = null)
    {
        $this->processor = $processor ?? new Processor();
    }

    /**
     * Processes an array of configurations.
     *
     * @param ConfigurationInterface $configuration The configuration class
     * @param array[]                $configs       An array of configuration items to process
     *
     * @return array The processed configuration
     */
    public function process(ConfigurationInterface $configuration, array $configs): array
    {
        $config = $this->processor->processConfiguration($configuration, $configs);

        foreach ($config as $type => &$choices) {
            foreach ($choices as &$choice) {
                if (!isset($choice['type'])) {
                    $choice['type'] = $type;
                }
            }
        }

        return $config;
    }
}
