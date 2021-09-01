<?php

declare(strict_types=1);

namespace Kreyu\Bundle\ApiFakerBundle\Tests\Application;

use Kreyu\Bundle\ApiFakerBundle\KreyuApiFakerBundle;
use Symfony\Bundle\FrameworkBundle\FrameworkBundle;
use Symfony\Bundle\FrameworkBundle\Kernel\MicroKernelTrait;
use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Kernel as BaseKernel;
use Symfony\Component\Routing\Loader\Configurator\RoutingConfigurator;

class Kernel extends BaseKernel
{
    use MicroKernelTrait;

    protected function configureContainer(ContainerBuilder $container, LoaderInterface $loader)
    {
        $container->loadFromExtension('framework', [
            'test' => true,
            'router' => [
                'utf8' => true,
            ],
        ]);

        $container->loadFromExtension('kreyu_api_faker', [
            'applications' => [
                [
                    'prefix' => '/test-api',
                    'endpoints' => [
                        [
                            'path' => '/endpoint-with-custom-method',
                            'method' => 'POST',
                        ], [
                            'path' => '/endpoint-with-custom-response-status-code',
                            'response' => [
                                'status_code' => 201,
                            ],
                        ], [
                            'path' => '/endpoint-with-custom-response-content',
                            'response' => [
                                'content' => [
                                    'foo' => 'bar',
                                    'lorem' => 'ipsum',
                                ],
                            ],
                        ],
                    ]
                ]
            ]
        ]);
    }

    protected function configureRoutes($routes)
    {
        if ($routes instanceof RoutingConfigurator) {
            $routes->import('.', 'kreyu_api_faker');
        } else {
            $routes->import('.', '', 'kreyu_api_faker');
        }
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