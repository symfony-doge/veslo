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
use HttpRuntimeException;
use Psr\Http\Message\RequestInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Veslo\AppBundle\Http\Proxy\Manager\Alfred;

/**
 * Http client that uses dynamic proxies and fake fingerprints to ensure requests stability
 */
class Batman implements ClientInterface
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
     */
    public function send(RequestInterface $request, array $options = [])
    {
        throw new HttpRuntimeException('Method is not supported.');
    }

    /**
     * {@inheritdoc}
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
        if ($this->options['proxy']['enabled']) {
            $options = array_replace_recursive($options, $this->getProxyOptions());
            // TODO: log appended stability options at debug level.
        }

        // TODO: fingerprint faking.

        return $this->httpClient->request($method, $uri, $options);
    }

    /**
     * {@inheritdoc}
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
