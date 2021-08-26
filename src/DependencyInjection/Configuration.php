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
                                                ->integerNode('status')
                                                    ->isRequired()
                                                ->end()
                                                ->scalarNode('body')->end()
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
