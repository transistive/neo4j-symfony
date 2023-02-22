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
            ->fixXmlConfig('driver')
            ->children()
                ->arrayNode('default_driver_config')
                    ->info('The default configuration for every driver')
                    ->defaultNull()
                    ->children()
                        ->scalarNode('acquire_connection_timeout')
                            ->info('The default timeout for acquiring a connection. Default is 60 seconds. Note that this is different from the transaction timeout.')
                        ->end()
                        ->scalarNode('user_agent')
                            ->info('The default user agent for every driver. Default is "neo4j-php-client/%client-version-numer%".')
                        ->end()
                    ->end()
                ->end()
                ->arrayNode('default_session_config')
                    ->info('The default configuration for every session')
                    ->defaultNull()
                    ->children()
                        ->scalarNode('fetch_size')->end()
                        ->enumNode('access_mode')
                            ->values(['READ', 'WRITE'])
                            ->info('The default access mode for every session. Default is WRITE.')
                        ->end()
                        ->scalarNode('database')
                            ->info('Select the standard database to use. Default is value is null, meaning the preconfigured database by the server is used (usually a database called neo4j).')
                        ->end()
                    ->end()
                ->end()
                ->arrayNode('default_transaction_config')
                    ->info('The default configuration for every transaction')
                    ->defaultNull()
                    ->children()
                        ->scalarNode('timeout')
                            ->defaultNull()
                            ->info('The default transaction timeout. If null is provided it will fall back tot he preconfigured timeout on the server')
                        ->end()
                    ->end()
                ->arrayNode('drivers')
                    ->info('List of drivers to use. If no drivers are configured the default driver will try to open a bolt connection without authentication on localhost over port 7687')
                    ->arrayPrototype()
                        ->fixXmlConfig('driver')
                        ->useAttributeAsKey('name')
                        ->children()
                            ->scalarNode('dsn')
                                ->info('The DSN for the driver. Default is "bolt://localhost:7687".')
                            ->end()
                            ->arrayNode('authentication')
                                ->info('The authentication for the driver')
                                ->defaultNull()
                                ->children()
                                    ->enumNode('type')
                                        ->info('The type of authentication')
                                        ->values(['basic', 'kerberos', 'dsn', 'none', 'oid'])
                                    ->end()
                                    ->scalarNode('username')->end()
                                    ->scalarNode('password')->end()
                                    ->scalarNode('token')->end()
                                ->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()
            ->end();

        return $treeBuilder;
    }

    private function buildDriverConfiguration(): void
    {
    }
}
