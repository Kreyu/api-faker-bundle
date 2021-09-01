<?php

declare(strict_types=1);

namespace Kreyu\Bundle\ApiFakerBundle\Pool;

use Kreyu\Bundle\ApiFakerBundle\Metadata\Endpoint;
use Kreyu\Bundle\ApiFakerBundle\Metadata\EndpointInterface;
use Kreyu\Bundle\ApiFakerBundle\Metadata\Factory\EndpointFactoryInterface;

class EndpointPool implements EndpointPoolInterface
{
    /**
     * @var array<string, EndpointInterface>
     */
    private $endpoints = [];

    private function __construct()
    {
        // ...
    }

    public static function createFromConfiguration(array $configuration, EndpointFactoryInterface $endpointFactory): self
    {
        $pool = new self;

        foreach ($configuration['applications'] as $application) {
            foreach ($application['endpoints'] as $endpoint) {
                $endpoint = $endpointFactory->createFromConfiguration($endpoint);

                $pool->endpoints[$endpoint->getId()] = $endpoint;
            }
        }

        return $pool;
    }

    public function getEndpoints(): array
    {
        return $this->endpoints;
    }

    public function getEndpointById(string $id): ?Endpoint
    {
        return $this->endpoints[$id] ?? null;
    }
}
