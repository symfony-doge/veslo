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

namespace Veslo\AppBundle\Event\Listener\Http\Proxy;

use Psr\Log\LoggerInterface;
use Veslo\AppBundle\Cache\CacherInterface;
use Veslo\AppBundle\Event\Http\Client\ConnectFailedEvent;
use Veslo\AppBundle\Http\ProxyAwareClientInterface;

/**
 * Calls a cache invalidator to reset a cached proxy list
 */
class CacheInvalidationListener
{
    /**
     * Adds a log record about cached proxy list invalidation
     *
     * @var LoggerInterface
     */
    private $logger;

    /**
     * Invalidates a cache instance
     *
     * @var CacherInterface
     */
    private $cacheInvalidator;

    /**
     * CacheInvalidationListener constructor.
     *
     * @param LoggerInterface $logger           Adds a log record about cached proxy list invalidation
     * @param CacherInterface $cacheInvalidator Invalidates a cache instance
     */
    public function __construct(LoggerInterface $logger, CacherInterface $cacheInvalidator)
    {
        $this->logger           = $logger;
        $this->cacheInvalidator = $cacheInvalidator;
    }

    /**
     * Invalidates a proxy list cache if client was unable to establish a connection to website
     *
     * @param ConnectFailedEvent $event Propagated whenever a HTTP client failed to establish connection to website
     *
     * @return void
     */
    public function onConnectFailed(ConnectFailedEvent $event): void
    {
        $httpClient = $event->getClient();

        if (! $httpClient instanceof ProxyAwareClientInterface || ! $httpClient->isProxyEnabled()) {
            return;
        }

        $connectException = $event->getConnectException();
        $context          = $connectException->getHandlerContext();

        if ($this->cacheInvalidator->invalidate()) {
            $this->logger->info('Proxy list cache has been invalidated due to connection problem.', $context);

            return;
        }

        $this->logger->error('Unable to invalidate a proxy list cache.');
    }
}
