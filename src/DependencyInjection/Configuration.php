<?php

namespace Hoten\OpenTracingBundle\DependencyInjection;

use OpenTracing\Formats;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('open_tracing');

        $rootNode
            ->children()
                ->scalarNode('enabled')->defaultValue(true)->end()
                ->scalarNode('sample')->defaultValue(0.001)->end()
                ->scalarNode('transport')->defaultValue('http')->end()
                ->scalarNode('format')->defaultValue(Formats\TEXT_MAP)->end()
                ->scalarNode('encoder')->defaultValue('json')->end()
                ->scalarNode('endpoint')->defaultValue('localhost:8126')->end()
                ->scalarNode('host')->defaultValue('localhost')->end()
                ->scalarNode('port')->defaultValue(7701)->end()
            ->end()
        ;

        return $treeBuilder;
    }
}
