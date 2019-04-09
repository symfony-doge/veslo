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

namespace Veslo\AnthillBundle\Vacancy\Roadmap\Strategy\HeadHunter\Api;

use DateTime;
use Exception;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\GuzzleException;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\Encoder\DecoderInterface;
use Veslo\AnthillBundle\Entity\Vacancy\Roadmap\Configuration\Parameters\HeadHunter as HeadHunterParameters;
use Veslo\AnthillBundle\Exception\Vacancy\Roadmap\Strategy\LookupFailedException;
use Veslo\AnthillBundle\Vacancy\Roadmap\ConfigurationInterface;
use Veslo\AnthillBundle\Vacancy\Roadmap\StrategyInterface;

/**
 * Represents vacancy search algorithm for HeadHunter site based on public API
 *
 * The problem is that we can't sort vacancies by publication_date in ascending order. Only in descending.
 * This algorithm performs vacancy fetching in ascending order by managing additional parameter - received count.
 * So it provides vacancy fetching in real time by their actual publication order.
 *
 * Request example:
 * https://api.hh.ru/vacancies?text=php&area=113&order_by=publication_time&date_from=2019-02-13T00:00:00&date_to=2019-02-14T00:00:00&per_page=1&page=0
 *
 * @see https://github.com/hhru/api/blob/master/docs/general.md
 */
class Version20190213 implements StrategyInterface
{
    /**
     * Maximum depth for internal solutions based on recursion
     *
     * @const int
     */
    private const MAX_RECURSION_CALLS = 3;

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
    private $_lastResponse;

    /**
     * Last resolved vacancy URL indexed by configuration key using in search
     *
     * @var array
     */
    private $_lastResolvedUrl;

    /**
     * Available recursion calls for internal solutions
     *
     * @var int
     */
    private $_recursionCallsAvailable;

    /**
     * Version20190213 constructor.
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

        $this->_lastResponse            = [];
        $this->_lastResolvedUrl         = [];
        $this->_recursionCallsAvailable = self::MAX_RECURSION_CALLS;
    }

    /**
     * {@inheritdoc}
     */
    public function lookup(ConfigurationInterface $configuration): ?string
    {
        /** @var HeadHunterParameters $parameters */
        $parameters       = $configuration->getParameters();
        $configurationKey = $parameters->getConfigurationKey();

        if (!empty($this->_lastResolvedUrl[$configurationKey])) {
            return $this->_lastResolvedUrl[$configurationKey];
        }

        $this->adjustDatesToCurrentDay($configuration);

        $found = $this->howMany($parameters);
        $page  = $this->determinePage($configuration, $found);

        if (null === $page) {
            return null;
        }

        // We already have a cached response for first page after howMany check.
        if (1 === $page) {
            return $this->resolveUrl($this->_lastResponse, $configurationKey);
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

        $this->_lastResolvedUrl[$configurationKey] = null;
        $this->_recursionCallsAvailable            = self::MAX_RECURSION_CALLS;
    }

    /**
     * Sets search publication date range to current day if it is not valid
     *
     * @param ConfigurationInterface $configuration Roadmap configuration with parameters for search algorithm
     *
     * @return void
     *
     * @throws Exception
     */
    private function adjustDatesToCurrentDay(ConfigurationInterface $configuration): void
    {
        /** @var HeadHunterParameters $parameters */
        $parameters = $configuration->getParameters();
        $today      = new DateTime('today');

        if ($parameters->getDateFrom() == $today) {
            return;
        }

        $parameters->setDateFrom($today);
        $tomorrow = new DateTime('tomorrow');
        $parameters->setDateTo($tomorrow);
        $parameters->setReceived(0);

        $configuration->save();
    }

    /**
     * Returns count of vacancies which are available on website at current time and satisfies search criteria
     *
     * @param HeadHunterParameters $parameters Parameters for vacancy search on HeadHunter website
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
     * @param ConfigurationInterface $configuration Roadmap configuration with parameters for search algorithm
     * @param int                    $page          Page number
     * @param int                    $found         Last fetched value of total vacancies available
     *
     * @return string|null
     */
    private function copyOnWriteLookup(ConfigurationInterface $configuration, int $page, int $found): ?string
    {
        /** @var HeadHunterParameters $parameters */
        $parameters = $configuration->getParameters();

        if (0 > $this->_recursionCallsAvailable) {
            $providerUri = $parameters->getProviderUri();

            throw LookupFailedException::providerIsUnstable($providerUri);
        }

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

        --$this->_recursionCallsAvailable;

        return $this->copyOnWriteLookup($configuration, $newPage, $newFound);
    }

    /**
     * Return vacancy data by specified search criteria
     * Response will be decoded from json to PHP array
     *
     * @param HeadHunterParameters $parameters Parameters for vacancy search on HeadHunter website
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
            $response = $this->httpClient->request(Request::METHOD_GET, $uri, ['query' => $query]);
        } catch (GuzzleException $e) {
            $this->logger->error(
                'Request to website failed.',
                [
                    'message' => $e->getMessage(),
                    'uri'     => $uri,
                    'query'   => $query,
                ]
            );

            throw LookupFailedException::withPrevious($e);
        }

        $json  = $response->getBody()->getContents();
        $array = $this->decoder->decode($json, 'json');

        return $this->_lastResponse = $array;
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
        if (array_key_exists('found', $response) && is_numeric($response['found'])) {
            return (int) $response['found'];
        }

        throw LookupFailedException::unexpectedResponse('found');
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
        // Symfony PropertyAccessor is not used due to unnecessary dependency overload.
        // This strategy requires only two fields to analyse: found count and url.
        if (!empty($response['items'])) {
            $item = array_shift($response['items']);

            if (!empty($item['url'])) {
                if (!empty($cacheKey)) {
                    $this->_lastResolvedUrl[$cacheKey] = $item['url'];
                }

                return $item['url'];
            }
        }

        throw LookupFailedException::unexpectedResponse('items.0.url');
    }

    /**
     * Returns page for next lookup
     * Encapsulates ascending order managing logic
     *
     * @param ConfigurationInterface $configuration Roadmap configuration with parameters for search algorithm
     * @param int                    $found         Freshly total count of vacancies for specified search criteria
     *
     * @return int|null Page number in 0..N range or null if no new vacancies
     */
    private function determinePage(ConfigurationInterface $configuration, int $found): ?int
    {
        // Provider can potentially remove or hide some vacancies.
        $received = $this->normalizeReceived($configuration, $found);

        // No new vacancies.
        if ($found === $received) {
            return null;
        }

        return $found - $received - 1;
    }

    /**
     * Returns received vacancies count synchronized with actual total count on website by search criteria
     * Also guarantees that page number cannot fall to less than 0 during page determination
     *
     * @param ConfigurationInterface $configuration Roadmap configuration with parameters for search algorithm
     * @param int                    $found         Vacancies total count for specified search criteria
     *
     * @return int
     */
    private function normalizeReceived(ConfigurationInterface $configuration, int $found): int
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
