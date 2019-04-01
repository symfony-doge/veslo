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

namespace Veslo\AppBundle\Cache;

use Symfony\Component\Cache\Adapter\AdapterInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Saves a value in the cache and invalidates it by demand
 *
 * Can be used if a group of services desire to share the same cache instance, namespace and keys scope, e.g.
 * service1 saves a cache item while service2 invalidates it by an event.
 *
 * It is not just an alternative to existent ProxyAdapter or something like that, but a bridge between Symfony-related
 * cache namespace and application service layer, because cache API in Symfony is varies due to different PSR
 * implementations (Simple/Advanced) and also moves to the separate Contracts namespace.
 *
 * So we can choose here which interface to use for our needs:
 * PSR-6 (https://github.com/symfony/symfony/tree/4.2/src/Symfony/Component/Cache/Adapter)
 * PSR-16 (https://github.com/symfony/symfony/tree/4.2/src/Symfony/Component/Cache/Simple)
 * or any custom adapter or a new one that implements symfony/contracts
 *
 * @see PSR-6 https://github.com/php-fig/cache/blob/master/src/CacheItemPoolInterface.php
 * @see PSR-16 https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-16-simple-cache.md#21-cacheinterface
 * @see symfony/contracts https://github.com/symfony/contracts/tree/master/Cache
 */
class Cacher
{
    /**
     * Cache instance for shared manipulation
     *
     * @var AdapterInterface
     */
    private $cache;

    /**
     * Cacher options
     *
     * @var array
     */
    private $options;

    /**
     * Cacher constructor.
     *
     * @param AdapterInterface $cache   Cache instance for shared manipulation
     * @param array            $options Options for Cacher
     */
    public function __construct(AdapterInterface $cache, array $options)
    {
        $this->cache = $cache;

        $optionsResolver = new OptionsResolver();
        $this->configureOptions($optionsResolver);

        $this->options = $optionsResolver->resolve($options);
    }

    /**
     * Returns positive if value was successfully saved in the cache
     *
     * @param mixed $value Value to be cached
     *
     * @return bool
     */
    public function save($value): bool
    {
        $cacheKey  = $this->options['key'];
        $cacheItem = $this->cache->getItem($cacheKey);

        $cacheItem->set($value);

        $cacheLifetime = $this->options['lifetime'];
        $cacheItem->expiresAfter($cacheLifetime);

        return $this->cache->save($cacheItem);
    }

    /**
     * Returns cached value or null if it not exists or expired
     *
     * @return mixed|null
     */
    public function get()
    {
        $cacheKey  = $this->options['key'];
        $cacheItem = $this->cache->getItem($cacheKey);

        return $cacheItem->isHit() ? $cacheItem->get() : null;
    }

    /**
     * Returns positive if cache was successfully invalidated
     *
     * @return bool
     */
    public function invalidate(): bool
    {
        return $this->cache->deleteItem($this->options['key']);
    }

    /**
     * Configures Cacher options
     *
     * @param OptionsResolver $optionsResolver Validates options and merges them with default values
     *
     * @return void
     */
    protected function configureOptions(OptionsResolver $optionsResolver): void
    {
        $optionsResolver->setDefaults(
            [
                'key'      => null,
                'lifetime' => null,
            ]
        );

        $optionsResolver->setRequired(['key']);
    }
}
