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
                ->arrayNode('applications')
                    ->arrayPrototype()
                        ->children()
                            ->scalarNode('name')->end()
                            ->scalarNode('prefix')->end()
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
                                            ->children()
                                                ->integerNode('status')->end()
                                                ->variableNode('body')
                                                    ->validate()
                                                        ->ifTrue(function ($v) {
                                                            return false === is_string($v) && false === is_array($v);
                                                        })
                                                        ->thenInvalid('Response body should be either string or array')
                                                    ->end()
                                                ->end()
                                            ->end()
                                        ->end() // response
                                    ->end()
                                ->end()
                            ->end() // endpoints
                        ->end()
                    ->end()
                ->end() // applications
            ->end()
        ;

        return $treeBuilder;
    }
}
