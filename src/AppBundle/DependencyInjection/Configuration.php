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
                ->arrayNode('http')
                    ->children()
                        ->append($this->getHttpClientNode())
                    ->end()
                ->end()
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
        $node        = $treeBuilder->root('client');

        $node
            ->children()
                ->booleanNode('logging')
                    ->defaultFalse()
                ->end()
                ->arrayNode('headers')
                    ->children()
                        ->scalarNode('user_agent')
                            ->isRequired()
                            ->cannotBeEmpty()
                        ->end()
                    ->end()
                ->end()
                ->append($this->getHttpClientProxyNode())
            ->end()
        ;

        return $node;
    }

    /**
     * Returns a proxy node for http client configuration tree
     *
     * @return ArrayNodeDefinition
     */
    private function getHttpClientProxyNode(): ArrayNodeDefinition
    {
        $treeBuilder = new TreeBuilder();
        $node        = $treeBuilder->root('proxy');

        $node
            ->canBeEnabled()
            ->children()
                ->arrayNode('dynamic')
                    ->children()
                        ->scalarNode('fetch_uri')
                            ->info('URI of proxy list from an external source for rotation')
                            ->example('https://proxy-provider.ltd/my_proxy_list')
                            ->treatFalseLike('')
                        ->end()
                        ->scalarNode('format')
                            ->info('Format in which proxy list are stored')
                            ->example('json')
                        ->end()
                        ->arrayNode('decoder_context')
                            ->info('Options that decoder have access to')
                            ->scalarPrototype()->end()
                        ->end()
                        ->arrayNode('cache')
                            ->info('Cache options for proxy list received by URI')
                            ->children()
                                ->scalarNode('key')->end()
                                ->scalarNode('lifetime')
                                    ->info('Time for which a cached proxy list is considered to be valid')
                                ->end()
                            ->end()
                        ->end()
                    ->end()
                    ->validate()
                        ->ifTrue(function ($v) {
                            return !empty($v['fetch_uri']) && empty($v['format']);
                        })
                        ->thenInvalid('Resource format must also be configured if \'fetch_uri\' is present %s')
                    ->end()
                ->end()
                ->arrayNode('static_list')
                    ->info('Is used by a default proxy locator (or fallback) if no others is present')
                    ->scalarPrototype()->end()
                ->end()
            ->end()
            ->beforeNormalization()
                ->ifTrue(function ($v) {
                    if (!$v['enabled']) {
                        return false;
                    }

                    return !is_array($v['static_list']) || !count($v['static_list']);
                })
                ->thenInvalid('Static proxy list must have at least one entry if proxy is enabled %s')
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
                ->scalarNode('heartbeat')
                    ->isRequired()
                    ->cannotBeEmpty()
                ->end()
            ->end()
        ;

        return $node;
    }
}
