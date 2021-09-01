<?php

declare(strict_types=1);

namespace Kreyu\Bundle\ApiFakerBundle\Routing;

use Kreyu\Bundle\ApiFakerBundle\Pool\EndpointPoolInterface;
use Symfony\Component\Config\Loader\Loader;
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;

class EndpointPoolLoader extends Loader
{
    public const LOADER_TYPE = 'kreyu_api_faker';
    public const ROUTE_ID_ATTRIBUTE = '_api_faker_id';

    private $endpointPool;
    private $controllerService;

    public function __construct(EndpointPoolInterface $endpointPool, string $controllerService)
    {
        if (method_exists(parent::class, '__construct')) {
            parent::__construct();
        }

        $this->endpointPool = $endpointPool;
        $this->controllerService = $controllerService;
    }

    public function load($resource, $type = null): RouteCollection
    {
        $routes = new RouteCollection();

        $endpoints = $this->endpointPool->getEndpoints();

        foreach ($endpoints as $endpoint) {
            $name = $endpoint->getRouteName();

            if (null !== $routes->get($name)) {
                continue;
            }

            $route = new Route($endpoint->getPath());

            $route->setMethods($endpoint->getMethod());
            $route->setDefault('_controller', $this->controllerService);
            $route->setDefault(self::ROUTE_ID_ATTRIBUTE, $endpoint->getId());

            $routes->add($name, $route);
        }

        return $routes;
    }

    public function supports($resource, $type = null): bool
    {
        return self::LOADER_TYPE === $type;
    }
}
