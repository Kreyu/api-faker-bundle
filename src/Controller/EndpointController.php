<?php

declare(strict_types=1);

namespace Kreyu\Bundle\ApiFakerBundle\Controller;

use Kreyu\Bundle\ApiFakerBundle\Pool\EndpointPoolInterface;
use Kreyu\Bundle\ApiFakerBundle\Response\Factory\EndpointResponseFactoryInterface;
use Kreyu\Bundle\ApiFakerBundle\Routing\EndpointPoolLoader;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class EndpointController
{
    private $endpointPool;
    private $responseFactory;

    public function __construct(EndpointPoolInterface $endpointPool, EndpointResponseFactoryInterface $responseFactory)
    {
        $this->endpointPool = $endpointPool;
        $this->responseFactory = $responseFactory;
    }

    public function __invoke(Request $request): Response
    {
        $endpoint = $this->endpointPool->getEndpointById(
            $request->attributes->get(EndpointPoolLoader::ROUTE_ID_ATTRIBUTE)
        );

        if (null === $endpoint) {
            throw new NotFoundHttpException();
        }

        return $this->responseFactory->createResponseForEndpoint($endpoint);
    }
}
