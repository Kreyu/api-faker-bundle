<?php

declare(strict_types=1);

namespace Kreyu\Bundle\ApiFakerBundle\Routing;

use Kreyu\Bundle\ApiFakerBundle\Configuration\ConfigurationPool;
use Symfony\Component\Config\Loader\Loader;
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;

class RouteLoader extends Loader
{
    private $configurationPool;
    private $isLoaded = false;

    public function __construct(ConfigurationPool $configurationPool, string $env = null)
    {
        parent::__construct($env);

        $this->configurationPool = $configurationPool;
    }

    public function load($resource, $type = null): RouteCollection
    {
        if (true === $this->isLoaded) {
            throw new \RuntimeException('Do not add the "kreyu_api_faker" loader twice');
        }

        $routes = new RouteCollection();

        $endpoints = $this->configurationPool->getEndpoints();

        foreach ($endpoints as $endpoint) {
            $route = new Route($endpoint->getPath());
            $route->setMethods($endpoint->getMethod());
            $route->setDefault('_controller', 'kreyu_api_faker.controller.endpoint_controller');

            $routes->add($endpoint->getPathSlug(), $route);
        }

        $this->isLoaded = true;

        return $routes;
    }

    public function supports($resource, $type = null): bool
    {
        return 'kreyu_api_faker' === $type;
    }
}
