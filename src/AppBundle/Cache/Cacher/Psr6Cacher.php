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

namespace Veslo\AppBundle\Cache\Cacher;

use Symfony\Component\Cache\Adapter\AdapterInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Veslo\AppBundle\Cache\CacherInterface;

/**
 * Uses the PSR-6 interface to manage cache items
 *
 * Note: it is designed mostly as DRY shortcut for sharing a single cache instance between multiple services.
 * You should consider to use the AdapterInterface directly if no sync between multiple services is required
 *
 * @see PSR-6 https://github.com/php-fig/cache/blob/master/src/CacheItemPoolInterface.php
 */
class Psr6Cacher implements CacherInterface
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
     * {@inheritdoc}
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
     * {@inheritdoc}
     */
    public function get()
    {
        $cacheKey  = $this->options['key'];
        $cacheItem = $this->cache->getItem($cacheKey);

        return $cacheItem->isHit() ? $cacheItem->get() : null;
    }

    /**
     * {@inheritdoc}
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
