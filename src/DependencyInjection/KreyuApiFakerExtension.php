<?php

declare(strict_types=1);

namespace Kreyu\Bundle\ApiFakerBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

class KreyuApiFakerExtension extends Extension
{
    public function load(array $configs, ContainerBuilder $container): void
    {
        $config = $this->processConfiguration(new Configuration, $configs);

        // Set default response status depending on the response body.
        foreach ($config['applications'] as &$application) {
            foreach ($application['endpoints'] as &$endpoint) {
                if (null === $endpoint['response']['status']) {
                    $endpoint['response']['status'] = null !== $endpoint['response']['body'] ? 200 : 204;
                }
            }
        }

        $container->setParameter('kreyu_api_faker.config', $config);

        $loader = new XmlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));
        $loader->load('services.xml');
    }
}
