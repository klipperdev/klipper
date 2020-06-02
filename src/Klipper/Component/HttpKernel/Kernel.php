<?php

/*
 * This file is part of the Klipper package.
 *
 * (c) François Pluchino <francois.pluchino@klipper.dev>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Klipper\Component\HttpKernel;

use Symfony\Bundle\FrameworkBundle\Kernel\MicroKernelTrait;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symfony\Component\HttpKernel\Kernel as BaseKernel;
use Symfony\Component\Routing\Loader\Configurator\RoutingConfigurator;

/**
 * @author François Pluchino <francois.pluchino@klipper.dev>
 */
class Kernel extends BaseKernel
{
    use MicroKernelTrait;

    public function getCacheDir(): string
    {
        $testChannel = false !== ($channel = getenv('ENV_TEST_CHANNEL_READABLE')) ? '/'.$channel : '';

        return parent::getCacheDir().$testChannel;
    }

    protected function configureContainer(ContainerConfigurator $container): void
    {
        $projectDir = $this->getProjectDir();
        $container->import($projectDir.'/config/{packages}/*.yaml');
        $container->import($projectDir.'/config/{packages}/'.$this->environment.'/*.yaml');
        $container->import($projectDir.'/config/{services}.yaml');
        $container->import($projectDir.'/config/{services}_'.$this->environment.'.yaml');
    }

    protected function configureRoutes(RoutingConfigurator $routes): void
    {
        $projectDir = $this->getProjectDir();
        $routes->import($projectDir.'/config/{routes}/'.$this->environment.'/*.yaml');
        $routes->import($projectDir.'/config/{routes}/*.yaml');
        $routes->import($projectDir.'/config/{routes}.yaml');
    }
}
