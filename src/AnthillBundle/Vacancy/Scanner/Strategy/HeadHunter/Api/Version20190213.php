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

namespace Veslo\AnthillBundle\Vacancy\Scanner\Strategy\HeadHunter\Api;

use Exception;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\GuzzleException;
use Psr\Log\LoggerInterface;
use Symfony\Component\PropertyAccess\PropertyAccessorInterface;
use Symfony\Component\Serializer\Encoder\DecoderInterface;
use Veslo\AnthillBundle\Dto\Vacancy\RawDto;
use Veslo\AnthillBundle\Exception\Vacancy\Scanner\Strategy\FetchFailedException;
use Veslo\AnthillBundle\Exception\Vacancy\Scanner\Strategy\TokenizationFailedException;
use Veslo\AnthillBundle\Vacancy\Scanner\StrategyInterface;

/**
 * Represents vacancy parsing algorithm for HeadHunter site based on public API
 * This is API so we doesn't need to parse a raw HTML, just need to decode some json
 */
class Version20190213 implements StrategyInterface
{
    /**
     * Describes schema translation for vacancy data, from external website format to local
     * Map structure: [propertyFrom => [propertyTo, isRequired]]
     *
     * @const array
     */
    private const PROPERTY_MAP = [
        '[alternate_url]'           => ['url', true],
        '[id]'                      => ['external_identifier', true],
        '[area][name]'              => ['region_name', true],
        '[employer][name]'          => ['company_name', true],
        '[employer][alternate_url]' => ['company_url', false],
        '[employer][logo_urls][90]' => ['company_logo_url', false],
        '[name]'                    => ['title', true],
        // No snippet.
        '[description]'             => ['text', true],
        '[salary][from]'            => ['salary_from', false],
        '[salary][to]'              => ['salary_to', false],
        '[salary][currency]'        => ['salary_currency', false],
        '[published_at]'            => ['publication_date', true],
    ];

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
     * Writes and reads values to/from an object/array graph
     *
     * @var PropertyAccessorInterface
     */
    private $propertyAccessor;

    /**
     * Version20190213 constructor.
     *
     * @param LoggerInterface           $logger           Logger
     * @param ClientInterface           $httpClient       Sends http requests
     * @param DecoderInterface          $decoder          Decodes data from specified format into PHP data
     * @param PropertyAccessorInterface $propertyAccessor Writes and reads values to/from an object/array graph
     */
    public function __construct(
        LoggerInterface $logger,
        ClientInterface $httpClient,
        DecoderInterface $decoder,
        PropertyAccessorInterface $propertyAccessor
    ) {
        $this->logger           = $logger;
        $this->httpClient       = $httpClient;
        $this->decoder          = $decoder;
        $this->propertyAccessor = $propertyAccessor;
    }

    /**
     * {@inheritdoc}
     */
    public function fetch(string $vacancyUrl): string
    {
        try {
            $response = $this->httpClient->request('GET', $vacancyUrl);
        } catch (GuzzleException $e) {
            $message = $e->getMessage();
            $this->logger->error('Request to website failed.', ['message' => $message, 'uri' => $vacancyUrl]);

            throw FetchFailedException::withPrevious($e);
        }

        $json = $response->getBody()->getContents();

        return $json;
    }

    /**
     * {@inheritdoc}
     *
     * "Tokenization" in case of API response is a simply property extraction.
     */
    public function tokenize(string $data): RawDto
    {
        $vacancy = new RawDto();

        try {
            $dataArray = $this->decoder->decode($data, 'json');
            $this->fillUp($vacancy, $dataArray);
        } catch (Exception $e) {
            $this->logger->critical(
                'Vacancy data tokenization failed.',
                [
                    'message' => $e->getMessage(),
                    'data'    => $data,
                ]
            );

            throw ($e instanceof TokenizationFailedException) ? $e : TokenizationFailedException::withPrevious($e);
        }

        return $vacancy;
    }

    /**
     * Sets vacancy dto fields with data from website API response
     *
     * @param RawDto $vacancy Vacancy dto
     * @param array  $data    Json with vacancy data from website API
     *
     * @return void
     */
    private function fillUp(RawDto $vacancy, array $data): void
    {
        foreach (self::PROPERTY_MAP as $propertyFrom => list($propertyTo, $isRequired)) {
            // Gets data part from external format.
            $propertyValue = $this->getProperty($data, $propertyFrom, $isRequired);

            // Sets data part in our local format.
            $this->setProperty($vacancy, $propertyTo, $propertyValue, $isRequired);
        }
    }

    /**
     * Returns value from specified place in data array
     *
     * @param array  $data         Json with vacancy data from website API
     * @param string $propertyPath Path to value
     * @param bool   $isRequired   Whenever value must present or exception will be propagated
     *
     * @return mixed The value at the end of the property path
     *
     * @throws TokenizationFailedException
     */
    private function getProperty(array $data, string $propertyPath, bool $isRequired)
    {
        try {
            return $this->propertyAccessor->getValue($data, $propertyPath);
        } catch (Exception $e) {
            if (!$isRequired) {
                return null;
            }

            $this->logger->error(
                'Failed to extract a part of vacancy data from external format.',
                [
                    'message'      => $e->getMessage(),
                    'propertyPath' => $propertyPath,
                    'isRequired'   => $isRequired,
                ]
            );

            throw TokenizationFailedException::unexpectedResponse($propertyPath);
        }
    }

    /**
     * Sets property value in vacancy dto
     *
     * @param RawDto $vacancy       Vacancy dto
     * @param string $propertyTo    Path in vacancy dto
     * @param mixed  $propertyValue Property value
     * @param bool   $isRequired    Whenever value must present or exception will be propagated
     *
     * @return void
     *
     * @throws TokenizationFailedException
     */
    private function setProperty(RawDto $vacancy, string $propertyTo, $propertyValue, bool $isRequired): void
    {
        try {
            $this->propertyAccessor->setValue($vacancy, $propertyTo, $propertyValue);
        } catch (Exception $e) {
            if (!$isRequired) {
                return;
            }

            $this->logger->error(
                'Failed to set a part of vacancy data in local format.',
                [
                    'message'           => $e->getMessage(),
                    'propertyTo'        => $propertyTo,
                    'propertyValueType' => gettype($propertyValue),
                    'propertyValue'     => $propertyValue,
                ]
            );

            $propertyConfig = array_filter(
                self::PROPERTY_MAP,
                function (array $propertyConfig) use ($propertyTo) {
                    return $propertyTo === $propertyConfig[0];
                }
            );

            $propertyPath = key($propertyConfig);

            throw TokenizationFailedException::unexpectedResponse($propertyPath);
        }
    }
}
