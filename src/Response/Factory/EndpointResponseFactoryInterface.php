<?php

declare(strict_types=1);

namespace Kreyu\Bundle\ApiFakerBundle\Response\Factory;

use Kreyu\Bundle\ApiFakerBundle\Metadata\EndpointInterface;
use Symfony\Component\HttpFoundation\Response;

interface EndpointResponseFactoryInterface
{
    public function createResponseForEndpoint(EndpointInterface $endpoint): Response;
}
