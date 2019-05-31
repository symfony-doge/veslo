<?php

/*
 * This file is part of the Veslo project <https://github.com/symfony-doge/veslo>.
 *
 * (C) 2019 Pavel Petrov <itnelo@gmail.com>.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @license https://opensource.org/licenses/GPL-3.0 GPL-3.0
 */

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
                                ->arrayNode('journal')
                                    ->children()
                                        ->integerNode('per_page')
                                            ->info('Vacancies count per page for rendering during list action')
                                            ->isRequired()
                                            ->min(1)
                                        ->end()
                                        ->integerNode('max_days_after_publication')
                                            ->info('How many days after publication are allowed for a vacancy to be rendered in "fresh" lists')
                                            ->isRequired()
                                            ->min(1)
                                        ->end()
                                    ->end()
                                ->end()
                                ->arrayNode('archive')
                                    ->children()
                                        ->integerNode('per_page')
                                            ->isRequired()
                                            ->min(1)
                                        ->end()
                                        ->integerNode('min_days_after_publication')
                                            ->info('Minimum days after publication that is required for a vacancy to be considered as "archived"')
                                            ->isRequired()
                                            ->min(1)
                                        ->end()
                                    ->end()
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
