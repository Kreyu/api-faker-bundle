<?php

declare(strict_types=1);

namespace Kreyu\Bundle\ApiFakerBundle\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class RouteController
{
    private $configuration;

    public function __construct(array $configuration)
    {
        $this->configuration = $configuration;
    }

    public function __invoke(Request $request): JsonResponse
    {
        $path = $request->getPathInfo();

        if ($endpoint = $this->getEndpointForPath($path)) {
            $response = $endpoint['response'];

            return new JsonResponse($response['body'], $response['status'], [], true);
        }

        return new JsonResponse(null, 404);
    }

    private function getEndpointForPath(string $path): ?array
    {
        foreach ($this->configuration['applications'] as $application) {
            foreach ($application['endpoints'] as $endpoint) {
                if ($endpoint['path'] === $path) {
                    return $endpoint;
                }
            }
        }

        return null;
    }
}
