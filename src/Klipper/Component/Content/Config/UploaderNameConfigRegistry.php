<?php

/*
 * This file is part of the Klipper package.
 *
 * (c) François Pluchino <francois.pluchino@klipper.dev>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Klipper\Component\Content\Config;

/**
 * @author François Pluchino <francois.pluchino@klipper.dev>
 */
class UploaderNameConfigRegistry implements UploaderNameConfigRegistryInterface
{
    /**
     * @var UploaderNameConfigInterface[]
     */
    private array $configs;

    public function __construct(array $configs = [])
    {
        foreach ($configs as $config) {
            $this->addConfig($config);
        }
    }

    public function addConfig(UploaderNameConfigInterface $config): void
    {
        $this->configs[] = $config;
    }

    public function getUploaderName($payload): ?string
    {
        foreach ($this->configs as $config) {
            if ($config->validate($payload)) {
                return $config->getUploaderName();
            }
        }

        return null;
    }
}
