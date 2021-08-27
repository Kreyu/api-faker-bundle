<?php

declare(strict_types=1);

namespace Kreyu\Bundle\ApiFakerBundle\Controller;

use Kreyu\Bundle\ApiFakerBundle\Configuration\ConfigurationPool;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class EndpointController
{
    private $configurationPool;

    public function __construct(ConfigurationPool $configurationPool)
    {
        $this->configurationPool = $configurationPool;
    }

    public function __invoke(Request $request): Response
    {
        $endpoint = $this->configurationPool->getEndpointForPath($request->getPathInfo());

        if (null === $endpoint) {
            throw new NotFoundHttpException();
        }

        $body = $endpoint->getResponse()->getBody();
        $status = $endpoint->getResponse()->getStatus();

        if (null === $body && $status !== Response::HTTP_NO_CONTENT) {
            return new Response($body, $status);
        }

        return new JsonResponse($body, $status, [], null !== $body);
    }
}
