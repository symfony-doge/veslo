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
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Middleware;
use Psr\Http\Message\RequestInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

/**
 * Http client that logs every request and response
 */
class VerboseClient implements ClientInterface
{
    /**
     * Logger as it is
     *
     * @var LoggerInterface
     */
    private $logger;

    /**
     * Converts an object into a set of arrays/scalars
     *
     * @var NormalizerInterface
     */
    private $normalizer;

    /**
     * Base http client implementation
     *
     * @var ClientInterface
     */
    private $httpClient;

    /**
     * VerboseClient constructor.
     *
     * @param LoggerInterface     $logger     Logger as it is
     * @param NormalizerInterface $normalizer Converts an object into a set of arrays/scalars
     * @param ClientInterface     $httpClient Base http client implementation
     */
    public function __construct(LoggerInterface $logger, NormalizerInterface $normalizer, ClientInterface $httpClient)
    {
        $this->logger     = $logger;
        $this->normalizer = $normalizer;
        $this->httpClient = $httpClient;
    }

    /**
     * {@inheritdoc}
     */
    public function send(RequestInterface $request, array $options = [])
    {
        return $this->httpClient->send($request, $options);
    }

    /**
     * {@inheritdoc}
     */
    public function sendAsync(RequestInterface $request, array $options = [])
    {
        return $this->httpClient->sendAsync($request, $options);
    }

    /**
     * {@inheritdoc}
     */
    public function request($method, $uri, array $options = [])
    {
        $transactions = [];
        $history      = Middleware::history($transactions);

        $handlerStack = HandlerStack::create();
        $handlerStack->push($history);

        $verboseOptions = [
            'handler' => $handlerStack,
        ];

        $optionsMerged = array_replace_recursive($options, $verboseOptions);

        $response = $this->httpClient->request($method, $uri, $optionsMerged);

        $transactionsNormalized = $this->normalizer->normalize($transactions);
        $this->logger->info('Http request sent.', ['transactions' => $transactionsNormalized]);

        $response->getBody()->rewind();

        return $response;
    }

    /**
     * {@inheritdoc}
     */
    public function requestAsync($method, $uri, array $options = [])
    {
        return $this->httpClient->requestAsync($method, $uri, $options);
    }

    /**
     * {@inheritdoc}
     */
    public function getConfig($option = null)
    {
        return $this->httpClient->getConfig($option);
    }
}
