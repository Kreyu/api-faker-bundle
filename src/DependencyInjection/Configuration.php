<?php

declare(strict_types=1);

namespace Kreyu\Bundle\ApiFakerBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder('kreyu_api_faker');

        $treeBuilder->getRootNode()
            ->children()
                ->append($this->getDefaultHeadersSection())
                ->append($this->getApplicationsSection())
            ->end()
            ->validate()
                ->always(function (array $config) {
                    foreach ($config['applications'] as &$application) {
                        foreach ($application['endpoints'] as &$endpoint) {

                            // Prepend endpoint path with application prefix, if given.
                            if (null !== $application['prefix']) {
                                $endpoint['path'] = trim($application['prefix'], '/') . '/' . trim($endpoint['path'], '/');
                            }

                            // If response status code is not given, set it to 200 or 204, depending on the response body content.
                            if (null === $endpoint['response']['status_code']) {
                                $endpoint['response']['status_code'] = null !== $endpoint['response']['content'] ? 200 : 204;
                            }

                            // If response headers are not given, use configured defaults.
                            if (empty($endpoint['response']['headers'])) {
                                $endpoint['response']['headers'] = $config['default_headers'];
                            }
                        }
                    }

                    return $config;
                })
            ->end()
        ;

        return $treeBuilder;
    }

    private function getDefaultHeadersSection()
    {
        $treeBuilder = new TreeBuilder('default_headers');

        return $treeBuilder->getRootNode()
            ->useAttributeAsKey('name')
            ->normalizeKeys(false)
            ->scalarPrototype()->end()
            ->defaultValue(['Content-Type' => 'application/json']);
    }

    private function getApplicationsSection()
    {
        $treeBuilder = new TreeBuilder('applications');

        return $treeBuilder->getRootNode()
            ->arrayPrototype()
                ->children()
                    ->scalarNode('prefix')
                        ->defaultNull()
                    ->end()
                    ->arrayNode('endpoints')
                        ->isRequired()
                        ->requiresAtLeastOneElement()
                        ->arrayPrototype()
                            ->children()
                                ->scalarNode('path')
                                    ->isRequired()
                                    ->cannotBeEmpty()
                                ->end()
                                ->scalarNode('method')
                                    ->defaultValue('GET')
                                ->end()
                                ->arrayNode('response')
                                    ->addDefaultsIfNotSet()
                                    ->children()
                                        ->integerNode('status_code')
                                            ->defaultNull()
                                        ->end()
                                        ->variableNode('content')
                                            ->defaultNull()
                                            ->validate()
                                                ->ifTrue(function ($v) {
                                                    return false === is_string($v) && false === is_array($v);
                                                })
                                                ->thenInvalid('Response body content should be either string or array')
                                            ->end()
                                        ->end()
                                        ->enumNode('content_format')
                                            ->values([null, 'json', 'xml', 'yaml', 'csv'])
                                            ->defaultValue('json')
                                        ->end()
                                        ->arrayNode('headers')
                                            ->useAttributeAsKey('name')
                                            ->normalizeKeys(false)
                                            ->scalarPrototype()->end()
                                        ->end()
                                    ->end()
                                ->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()
            ->end();
    }
}
