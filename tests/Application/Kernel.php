<?php

declare(strict_types=1);

namespace Kreyu\Bundle\ApiFakerBundle\Tests\Application;

use Kreyu\Bundle\ApiFakerBundle\KreyuApiFakerBundle;
use Symfony\Bundle\FrameworkBundle\FrameworkBundle;
use Symfony\Bundle\FrameworkBundle\Kernel\MicroKernelTrait;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symfony\Component\HttpKernel\Kernel as BaseKernel;
use Symfony\Component\Routing\Loader\Configurator\RoutingConfigurator;

class Kernel extends BaseKernel
{
    use MicroKernelTrait;

    public function configureRoutes(RoutingConfigurator $routes): void
    {
        $routes->import(__DIR__ . '/config/routes.yaml');
    }

    public function configureContainer(ContainerConfigurator $container): void
    {
        $container->import(__DIR__ . '/config/config.yml');
    }

    public function getProjectDir(): string
    {
        return __DIR__;
    }

    public function registerBundles(): iterable
    {
        return [
            new FrameworkBundle(),
            new KreyuApiFakerBundle(),
        ];
    }
}