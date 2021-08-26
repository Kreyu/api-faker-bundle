<?php

declare(strict_types=1);

namespace Kreyu\Bundle\ApiFakerBundle\Routing;

use Kreyu\Bundle\ApiFakerBundle\Controller\RouteController;
use Symfony\Component\Config\Loader\Loader;
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;

class RouteLoader extends Loader
{
    private $configuration;
    private $isLoaded = false;

    public function __construct(array $configuration)
    {
        $this->configuration = $configuration;
    }

    public function load($resource, $type = null): RouteCollection
    {
        if (true === $this->isLoaded) {
            throw new \RuntimeException('Do not add the "kreyu_api_faker" loader twice');
        }

        $routes = new RouteCollection();

        foreach ($this->configuration['applications'] as $application) {
            foreach ($application['endpoints'] as $index => $endpoint) {
                $path = trim($endpoint['path'], '/');

                if (!empty($application['prefix'])) {
                    $path = trim($application['prefix'], '/') . '/' . $path;
                }

                $route = new Route($path);
                $route->setMethods($endpoint['method']);
                $route->setDefault('_controller', sprintf('%s::__invoke', RouteController::class));

                $name = $this->generateRouteName($application['name'], $index);

                $routes->add($name, $route);
            }
        }

        $this->isLoaded = true;

        return $routes;
    }

    public function supports($resource, $type = null): bool
    {
        return 'kreyu_api_faker' === $type;
    }

    private function generateRouteName(string $applicationName, int $index): string
    {
        $applicationName = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '_', $applicationName)));

        return sprintf('%s_%d', $applicationName, $index);
    }
}
