<?php

namespace DahRomy\MVola\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder('mvola');
        $rootNode = $treeBuilder->getRootNode();

        $rootNode
            ->children()
                ->scalarNode('environment')->defaultValue('sandbox')->end()
                ->scalarNode('merchant_number')->isRequired()->cannotBeEmpty()->end()
                ->scalarNode('company_name')->isRequired()->cannotBeEmpty()->end()
                ->scalarNode('consumer_key')->isRequired()->cannotBeEmpty()->end()
                ->scalarNode('consumer_secret')->isRequired()->cannotBeEmpty()->end()
                ->scalarNode('auth_url')->isRequired()->end()
                ->integerNode('max_retries')->defaultValue(3)->end()
                ->integerNode('retry_delay')->defaultValue(1000)->end()
                ->integerNode('cache_ttl')->defaultValue(3600)->end()
            ->end()
        ;

        return $treeBuilder;
    }
}
