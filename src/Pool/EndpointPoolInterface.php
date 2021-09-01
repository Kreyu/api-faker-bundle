<?php

declare(strict_types=1);

namespace Kreyu\Bundle\ApiFakerBundle\Pool;

use Kreyu\Bundle\ApiFakerBundle\Metadata\Endpoint;
use Kreyu\Bundle\ApiFakerBundle\Metadata\EndpointInterface;

interface EndpointPoolInterface
{
    /**
     * @var array<EndpointInterface>
     */
    public function getEndpoints(): array;

    public function getEndpointById(string $id): ?Endpoint;
}