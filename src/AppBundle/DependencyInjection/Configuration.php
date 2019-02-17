<?php

declare(strict_types=1);

namespace Veslo\AppBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * AppBundle configuration.
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode    = $treeBuilder->root('veslo_app');

        $rootNode
            ->children()
                ->append($this->getHttpClientNode())
                ->append($this->getAmqpClientNode())
                ->arrayNode('workflow')
                    ->children()
                        ->arrayNode('vacancy_research')
                            ->children()
                                ->arrayNode('transitions')
                                    ->children()
                                        ->scalarNode('queue_prefix')
                                            ->isRequired()
                                            ->cannotBeEmpty()
                                        ->end()
                                    ->end()
                                ->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()
            ->end()
        ;

        return $treeBuilder;
    }

    /**
     * Returns a global http client node
     *
     * @return ArrayNodeDefinition
     */
    private function getHttpClientNode(): ArrayNodeDefinition
    {
        $treeBuilder = new TreeBuilder();
        $node        = $treeBuilder->root('http_client');

        $node
            ->children()
                ->arrayNode('headers')
                    ->children()
                        ->scalarNode('user_agent')
                            ->isRequired()
                            ->cannotBeEmpty()
                        ->end()
                    ->end()
                ->end()
            ->end()
        ;

        return $node;
    }

    /**
     * Returns a global amqp client node
     *
     * @return ArrayNodeDefinition
     */
    private function getAmqpClientNode(): ArrayNodeDefinition
    {
        $treeBuilder = new TreeBuilder();
        $node        = $treeBuilder->root('amqp_client');

        $node
            ->children()
                ->scalarNode('host')
                    ->isRequired()
                    ->cannotBeEmpty()
                ->end()
                ->scalarNode('vhost')
                    ->isRequired()
                    ->cannotBeEmpty()
                ->end()
                ->scalarNode('user')
                    ->isRequired()
                    ->cannotBeEmpty()
                ->end()
                ->scalarNode('password')
                    ->isRequired()
                    ->cannotBeEmpty()
                ->end()
            ->end()
        ;

        return $node;
    }
}
