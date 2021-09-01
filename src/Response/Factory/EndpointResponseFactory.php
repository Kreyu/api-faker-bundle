<?php

declare(strict_types=1);

namespace Kreyu\Bundle\ApiFakerBundle\Response\Factory;

use Kreyu\Bundle\ApiFakerBundle\Metadata\EndpointInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\SerializerInterface;

class EndpointResponseFactory implements EndpointResponseFactoryInterface
{
    private $serializer;

    public function __construct(SerializerInterface $serializer)
    {
        $this->serializer = $serializer;
    }

    public function createResponseForEndpoint(EndpointInterface $endpoint): Response
    {
        $response = new Response();
        $response->setStatusCode($endpoint->getStatusCode());

        foreach ($endpoint->getHeaders() as $key => $value) {
            $response->headers->set($key, $value);
        }

        $content = $endpoint->getContent();

        if (is_string($content)) {
            if (is_file($content)) {
                $content = file_get_contents($content);
            }
        }

        if (null !== $format = $endpoint->getContentFormat()) {
            $content = $this->serializer->serialize($content, $format);
        }

        $response->setContent($content);

        return $response;
    }
}
