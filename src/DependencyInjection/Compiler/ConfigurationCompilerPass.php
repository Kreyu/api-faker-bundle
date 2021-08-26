<?php

declare(strict_types=1);

namespace Kreyu\Bundle\ApiFakerBundle\DependencyInjection\Compiler;

use Kreyu\Bundle\ApiFakerBundle\Routing\RouteLoader;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class ConfigurationCompilerPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        $service = $container->getDefinition(RouteLoader::class);

        $service->setArgument('$configuration', $container->getParameter('kreyu_api_faker.config'));
    }
}
