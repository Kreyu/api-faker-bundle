<?php

declare(strict_types=1);

namespace Kreyu\Bundle\ApiFakerBundle\Metadata\Factory;

use Kreyu\Bundle\ApiFakerBundle\Metadata\Endpoint;
use Kreyu\Bundle\ApiFakerBundle\Metadata\EndpointInterface;

class EndpointFactory implements EndpointFactoryInterface
{
    public function createFromConfiguration(array $configuration): EndpointInterface
    {
        return new Endpoint(
            $configuration['method'],
            $configuration['path'],
            $configuration['response']['status_code'],
            $configuration['response']['content'],
            $configuration['response']['content_format'],
            $configuration['response']['headers']
        );
    }
}
