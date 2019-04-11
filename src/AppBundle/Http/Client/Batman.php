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

namespace Veslo\AppBundle\Http\Client;

use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\ConnectException;
use HttpRuntimeException;
use Psr\Http\Message\RequestInterface;
use Symfony\Component\Console\ConsoleEvents;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Veslo\AppBundle\Event\Http\Client\ConnectFailedEvent;
use Veslo\AppBundle\Http\Proxy\Manager\Alfred;
use Veslo\AppBundle\Http\ProxyAwareClientInterface;

/**
 * Http client that uses dynamic proxies and fake fingerprints to ensure requests stability
 *
 * Configured for using in console environment (using ConsoleEvents)
 */
class Batman implements ClientInterface, ProxyAwareClientInterface
{
    /**
     * Base http client implementation
     *
     * @var ClientInterface
     */
    private $httpClient;

    /**
     * Provides proxies for requests
     *
     * @var Alfred
     */
    private $proxyManager;

    /**
     * Dispatches a connect failed event to listeners
     *
     * @var EventDispatcherInterface|null
     */
    private $eventDispatcher;

    /**
     * Options for stable http client
     *
     * @var array
     */
    private $options;

    /**
     * Batman constructor.
     *
     * @param ClientInterface $httpClient   Base http client implementation
     * @param Alfred          $proxyManager Provides proxies for requests
     * @param array           $options      Options for stable http client
     */
    public function __construct(ClientInterface $httpClient, Alfred $proxyManager, array $options)
    {
        $this->httpClient   = $httpClient;
        $this->proxyManager = $proxyManager;

        $optionsResolver = new OptionsResolver();
        $optionsResolver->setDefaults(
            [
                'proxy' => [
                    'enabled' => false,
                ],
            ]
        );

        $this->options = $optionsResolver->resolve($options);
    }

    /**
     * {@inheritdoc}
     *
     * @throws HttpRuntimeException
     */
    public function send(RequestInterface $request, array $options = [])
    {
        throw new HttpRuntimeException('Method is not supported.');
    }

    /**
     * {@inheritdoc}
     *
     * @throws HttpRuntimeException
     */
    public function sendAsync(RequestInterface $request, array $options = [])
    {
        throw new HttpRuntimeException('Method is not supported.');
    }

    /**
     * {@inheritdoc}
     */
    public function request($method, $uri, array $options = [])
    {
        if ($this->isProxyEnabled()) {
            $options = array_replace_recursive($options, $this->getProxyOptions());

            // TODO: log appended stability options at debug level.
        }

        // TODO: fingerprint faking.

        try {
            return $this->httpClient->request($method, $uri, $options);
        } catch (ConnectException $e) {
            $eventDispatcher = $this->eventDispatcher;

            // we are "scheduled" to execute some health maintenance tasks on-the-fly during kernel's terminate stage.
            // as an alternative, it could be implemented as a custom middleware for the HTTP client.
            if ($eventDispatcher instanceof EventDispatcherInterface) {
                $eventDispatcher->addListener(
                    ConsoleEvents::TERMINATE,
                    function () use ($eventDispatcher, $e) {
                        $connectFailedEvent = new ConnectFailedEvent($this, $e);
                        $eventDispatcher->dispatch(ConnectFailedEvent::NAME, $connectFailedEvent);
                    }
                );
            }

            throw $e;
        }
    }

    /**
     * {@inheritdoc}
     *
     * @throws HttpRuntimeException
     */
    public function requestAsync($method, $uri, array $options = [])
    {
        throw new HttpRuntimeException('Method is not supported.');
    }

    /**
     * {@inheritdoc}
     */
    public function getConfig($option = null)
    {
        return $this->httpClient->getConfig($option);
    }

    /**
     * {@inheritdoc}
     */
    public function isProxyEnabled(): bool
    {
        return !! $this->options['proxy']['enabled'];
    }

    /**
     * Sets an event dispatcher for processing a connect failed event
     *
     * @param EventDispatcherInterface $eventDispatcher
     *
     * @return void
     */
    public function setEventDispatcher(EventDispatcherInterface $eventDispatcher): void
    {
        $this->eventDispatcher = $eventDispatcher;
    }

    /**
     * Returns proxy-related options for http client
     *
     * @return array
     */
    private function getProxyOptions(): array
    {
        $proxy   = $this->proxyManager->getProxy();
        $options = ['proxy' => $proxy];

        return $options;
    }
}
