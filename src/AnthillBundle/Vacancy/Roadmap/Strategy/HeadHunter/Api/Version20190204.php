<?php

declare(strict_types=1);

namespace Veslo\AnthillBundle\Vacancy\Roadmap\Strategy\HeadHunter\Api;

use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\GuzzleException;
use Psr\Log\LoggerInterface;
use Symfony\Component\Serializer\Encoder\DecoderInterface;
use Veslo\AnthillBundle\Entity\Vacancy\Roadmap\Configuration\Parameters\HeadHunter as HeadHunterParameters;
use Veslo\AnthillBundle\Exception\Roadmap\StrategyException;
use Veslo\AnthillBundle\Vacancy\Roadmap\ConfigurationInterface;
use Veslo\AnthillBundle\Vacancy\Roadmap\StrategyInterface;

/**
 * Represents vacancy searching algorithm for HeadHunter site based on public API
 * https://github.com/hhru/api/blob/master/docs/general.md
 */
class Version20190204 implements StrategyInterface
{
    /**
     * Logger
     *
     * @var LoggerInterface
     */
    private $logger;

    /**
     * Sends http requests
     *
     * @var ClientInterface
     */
    private $httpClient;

    /**
     * Decodes data from specified format into PHP data
     *
     * @var DecoderInterface
     */
    private $decoder;

    /**
     * Decoded last response from website
     *
     * @var array
     */
    private $_last_response;

    /**
     * Last resolved vacancy URL indexed by;
     *
     * @var array
     */
    private $_last_resolved_url;

    /**
     * Maximum depth for internal solutions based on recursion.
     *
     * @var int
     */
    private $_recursion_calls_available = 3;

    /**
     * Version20190204 constructor.
     *
     * @param LoggerInterface  $logger     Logger
     * @param ClientInterface  $httpClient Sends http requests
     * @param DecoderInterface $decoder    Decodes data from specified format into PHP data
     */
    public function __construct(LoggerInterface $logger, ClientInterface $httpClient, DecoderInterface $decoder)
    {
        $this->logger     = $logger;
        $this->httpClient = $httpClient;
        $this->decoder    = $decoder;

        $this->_last_response     = [];
        $this->_last_resolved_url = [];
    }

    /**
     * {@inheritdoc}
     */
    public function lookup(ConfigurationInterface $configuration): ?string
    {
        /** @var HeadHunterParameters $parameters */
        $parameters       = $configuration->getParameters();
        $configurationKey = $parameters->getConfigurationKey();

        if (!empty($this->_last_resolved_url[$configurationKey])) {
            return $this->_last_resolved_url[$configurationKey];
        }

        // TODO: adjust dates (and received) for current day if needed.

        $found = $this->howMany($parameters);
        $page  = $this->determinePage($configuration, $found);

        if (null === $page) {
            return null;
        }

        // We already have a cached response for first page after howMany check.
        if (1 === $page) {
            return $this->resolveUrl($this->_last_response, $configurationKey);
        }

        return $this->copyOnWriteLookup($configuration, $page, $found);
    }

    /**
     * {@inheritdoc}
     */
    public function iterate(ConfigurationInterface $configuration): void
    {
        /** @var HeadHunterParameters $parameters */
        $parameters = $configuration->getParameters();
        $received   = $parameters->getReceived();

        $parameters->setReceived($received + 1);
        $configuration->save();

        $configurationKey = $parameters->getConfigurationKey();
        $this->_last_resolved_url[$configurationKey] = null;
    }

    /**
     * Returns count of vacancies which are available on website at current time and satisfies search criteria
     *
     * @param HeadHunterParameters $parameters Parameters for vacancy searching on HeadHunter website
     *
     * @return int
     */
    private function howMany(HeadHunterParameters $parameters): int
    {
        $response = $this->iAmCurious($parameters);

        return $this->resolveFound($response);
    }

    /**
     * Returns actual page content with guarantees that it is not changed during algorithm execution
     *
     * To prevent invalid page read between search query and actual fetching query, $found should not be changed.
     * If $found changes it means some vacancy has been added or deleted during lookup
     * and we should try again to ensure consistency with real page content
     *
     * @param ConfigurationInterface $configuration Roadmap configuration with parameters for searching algorithm
     * @param int                    $page          Page number
     * @param int                    $found         Last fetched value of total vacancies available
     *
     * @return string|null
     */
    private function copyOnWriteLookup(ConfigurationInterface $configuration, int $page, int $found): ?string
    {
        if (0 > $this->_recursion_calls_available) {
            throw StrategyException::providerIsBusy();
        }

        /** @var HeadHunterParameters $parameters */
        $parameters = $configuration->getParameters();

        $response = $this->iAmCurious($parameters, $page);
        $newFound = $this->resolveFound($response);

        // Vacancy count has not been changed while we actually tried to fetch it.
        if ($newFound === $found) {
            $configurationKey = $parameters->getConfigurationKey();

            return $this->resolveUrl($response, $configurationKey);
        }

        // Otherwise some vacancy could be deleted or hidden, we should sync our configuration parameters.
        // Retrying to determine correct page for next vacancy lookup using new maximum vacancy count.
        $newPage = $this->determinePage($configuration, $newFound);

        if (null === $newPage) {
            return null;
        }

        --$this->_recursion_calls_available;

        return $this->copyOnWriteLookup($configuration, $newPage, $newFound);
    }

    /**
     * Return vacancy data by specified searching criteria
     * Response will be decoded from json to PHP array
     *
     * @param HeadHunterParameters $parameters Parameters for vacancy searching on HeadHunter website
     * @param int                  $page       Page number
     *
     * @return array
     */
    private function iAmCurious(HeadHunterParameters $parameters, int $page = 0): array
    {
        $uri   = $parameters->getUrl();
        $query = [
            'text'      => $parameters->getText(),
            'order_by'  => $parameters->getOrderBy(),
            'date_from' => $parameters->getDateFromFormatted(),
            'date_to'   => $parameters->getDateToFormatted(),
            'per_page'  => $parameters->getPerPage(),
            'page'      => $page,
        ];

        $area = $parameters->getArea();

        if (!empty($area)) {
            $query['area'] = $area;
        }

        try {
            $response = $this->httpClient->request('GET', $uri, ['query' => $query]);
        } catch (GuzzleException $e) {
            $this->logger->error('Request to website failed.', ['uri' => $uri, 'query' => $query]);

            throw StrategyException::withPrevious($e);
        }

        $json  = $response->getBody()->getContents();
        $array = $this->decoder->decode($json, 'json');

        return $this->_last_response = $array;
    }

    /**
     * Returns count of vacancies found retrieved from response
     *
     * @param array $response Response from website
     *
     * @return int
     */
    private function resolveFound(array $response): int
    {
        if (!array_key_exists('found', $response) || !is_numeric($response['found'])) {
            throw StrategyException::unexpectedResponse('found');
        }

        return (int) $response['found'];
    }

    /**
     * Returns vacancy URL for parsing retrieved from response
     *
     * @param array  $response Response from website
     * @param string $cacheKey Whenever result should be cached until next iterate() call
     *
     * @return string
     */
    private function resolveUrl(array $response, ?string $cacheKey = null): string
    {
        if (array_key_exists('items', $response)) {
            $items = $response['items'];

            if (!empty($items)) {
                $item = array_shift($items);

                if (array_key_exists('url', $item)) {
                    if (!empty($cacheKey)) {
                        $this->_last_resolved_url[$cacheKey] = $item['url'];
                    }

                    return $item['url'];
                }
            }
        }

        throw StrategyException::unexpectedResponse('items.0.url');
    }

    // TODO: descr
    private function determinePage(ConfigurationInterface $configuration, int $found): ?int
    {
        // HeadHunter can potentially remove or hide some vacancies.
        $received = $this->normalizeReceived($configuration, $found);

        // No new vacancies.
        if ($found === $received) {
            return null;
        }

        return $found - $received - 1;
    }

    // TODO: descr
    private function normalizeReceived(ConfigurationInterface $configuration, int $found)
    {
        /** @var HeadHunterParameters $parameters */
        $parameters = $configuration->getParameters();
        $received   = $parameters->getReceived();

        if ($received <= $found) {
            return $received;
        }

        $parameters->setReceived($found);
        $configuration->save();

        return $found;
    }
}
