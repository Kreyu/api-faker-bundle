<?php

declare(strict_types=1);

namespace Kreyu\Bundle\ApiFakerBundle\Configuration;

use Kreyu\Bundle\ApiFakerBundle\Configuration\Model\Application;
use Kreyu\Bundle\ApiFakerBundle\Configuration\Model\Endpoint;
use Kreyu\Bundle\ApiFakerBundle\Configuration\Model\Response;

class ConfigurationPool
{
    /**
     * @var array<Application>
     */
    private $applications;

    private function __construct()
    {
        // ...
    }

    public static function createFromConfiguration(array $configuration): self
    {
        $pool = new self;

        foreach ($configuration['applications'] as $application) {
            $endpoints = [];

            foreach ($application['endpoints'] as $endpoint) {
                $path = trim($endpoint['path'], '/');

                if (!empty($application['prefix'])) {
                    $path = trim($application['prefix'], '/') . '/' . $path;
                }

                $endpoints[] = new Endpoint($path, $endpoint['method'], new Response(
                    $endpoint['response']['status'] ?? null,
                    $endpoint['response']['body'] ?? null
                ));
            }

            $pool->applications[] = new Application(
                $application['name'],
                $application['prefix'] ?? null,
                $endpoints
            );
        }

        return $pool;
    }

    public function getApplications(): array
    {
        return $this->applications;
    }

    /**
     * @return array<Endpoint>
     */
    public function getEndpoints(): array
    {
        $endpoints = [];

        foreach ($this->applications as $application) {
            array_push($endpoints, ...$application->getEndpoints());
        }

        return $endpoints;
    }

    public function getEndpointForPath(string $path): ?Endpoint
    {
        foreach ($this->applications as $application) {
            foreach ($application->getEndpoints() as $endpoint) {
                if (str_ends_with($path, $endpoint->getPath())) {
                    return $endpoint;
                }
            }
        }

        return null;
    }
}