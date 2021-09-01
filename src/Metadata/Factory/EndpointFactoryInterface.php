<?php

declare(strict_types=1);

namespace Kreyu\Bundle\ApiFakerBundle\Metadata\Factory;

use Kreyu\Bundle\ApiFakerBundle\Metadata\EndpointInterface;

interface EndpointFactoryInterface
{
    public function createFromConfiguration(array $configuration): EndpointInterface;
}
