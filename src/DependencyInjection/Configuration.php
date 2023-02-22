<?php

declare(strict_types=1);

namespace Neo4j\Neo4jBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This class contains the configuration information for the bundle.
 *
 * This information is solely responsible for how the different configuration
 * sections are normalized, and merged.
 *
 * @author Tobias Nyholm <tobias.nyholm@gmail.com>
 */
class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder('neo4j');

        $treeBuilder->getRootNode()
            ->children()
            ->arrayNode('profiling')
                ->addDefaultsIfNotSet()
                ->treatFalseLike(['enabled' => false])
                ->treatTrueLike(['enabled' => true])
                ->treatNullLike(['enabled' => false])
                ->info('Extend the web profiler with information about queries.')
                ->children()
                    ->booleanNode('enabled')
                        ->info('Turn the toolbar on or off. Defaults to kernel debug mode.')
                        ->defaultValue('%kernel.debug%')
                    ->end()
                ->end()
            ->end()
            ->arrayNode('clients')
                ->requiresAtLeastOneElement()
                ->useAttributeAsKey('name')
                ->prototype('array')
                ->addDefaultsIfNotSet()
                ->fixXmlConfig('connection')
                ->children()
                    ->arrayNode('connections')
                        ->prototype('scalar')->end()
                    ->end()
                ->end()
            ->end()->end()
            ->arrayNode('connections')
                ->isRequired()
                ->requiresAtLeastOneElement()
                ->useAttributeAsKey('name')
                ->prototype('array')
                ->addDefaultsIfNotSet()
                ->children()
                    ->enumNode('scheme')->values(['http', 'bolt'])->defaultValue('bolt')->end()
                    ->scalarNode('host')->defaultValue('localhost')->end()
                    ->scalarNode('port')->end()
                    ->scalarNode('username')->defaultValue('neo4j')->end()
                    ->scalarNode('password')->defaultValue('neo4j')->end()
                    ->scalarNode('dsn')->defaultNull()->end()
                ->end()
            ->end()
        ->end();

        return $treeBuilder;
    }
}
