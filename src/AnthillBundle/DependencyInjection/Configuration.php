<?php

declare(strict_types=1);

namespace Veslo\AnthillBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * AnthillBundle configuration.
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode    = $treeBuilder->root('veslo_anthill');

        $rootNode
            ->children()
                ->arrayNode('vacancy')
                    ->children()
                        ->arrayNode('digging')
                            ->children()
                                ->arrayNode('roadmap')
                                    ->children()
                                        ->integerNode('attempts_until_pause')
                                            ->info('How much vacancy search attempts cranky dung beetle should perform via roadmap before give up')
                                            ->isRequired()
                                            ->min(1)
                                        ->end()
                                        ->integerNode('pause_duration')
                                            ->info('Give up time in seconds for cranky dung beetle after too much unsuccessful vacancy search attempts')
                                            ->isRequired()
                                            ->min(10)
                                        ->end()
                                    ->end()
                                ->end()
                            ->end()
                        ->end()
                        ->arrayNode('provider')
                            ->info('Options for vacancy providers')
                            ->children()
                                ->integerNode('per_page')
                                    ->info('Vacancies count per page for rendering during list action')
                                    ->isRequired()
                                    ->min(1)
                                ->end()
                            ->end()
                        ->end()
                        ->arrayNode('repository')
                            ->info('Options for vacancy repository')
                            ->children()
                                ->scalarNode('cache_result_namespace')
                                    ->info('Query result cache namespace for vacancy repository')
                                    ->isRequired()
                                    ->cannotBeEmpty()
                                ->end()
                                ->integerNode('cache_result_lifetime')
                                    ->info('How long query result cache will be available after successful put; during this time some data changes on website will not be seen, for example, vacancy list')
                                    ->isRequired()
                                    ->min(0)
                                ->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()
            ->end()
        ;

        return $treeBuilder;
    }
}
