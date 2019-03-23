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

namespace Veslo\SanityBundle\Vacancy\Analyser;

use Symfony\Component\OptionsResolver\OptionsResolver;
use SymfonyDoge\MinistryOfTruthClient\Bridge\Symfony\Credentials\StorageInterface as CredentialsStorageInterface;
use SymfonyDoge\MinistryOfTruthClient\ClientInterface;
use SymfonyDoge\MinistryOfTruthClient\Dto\Request\Index\RequestDto as IndexRequest;
use SymfonyDoge\MinistryOfTruthClient\Enum\Request\Index\Context;
use Veslo\AnthillBundle\Entity\Vacancy;
use Veslo\AppBundle\Integration\MinistryOfTruth\LocaleOptionsTrait;
use Veslo\SanityBundle\Dto\Vacancy\IndexDto;
use Veslo\SanityBundle\Vacancy\Analyser\DataConverter\MinistryOfTruth as DataConverter;
use Veslo\SanityBundle\Vacancy\AnalyserInterface;

/**
 * Provides vacancy index, tags and other sanity data for a vacancy by external Ministry of Truth microservice
 */
class MinistryOfTruth implements AnalyserInterface
{
    use LocaleOptionsTrait {
        configureLocaleOptions as protected;
    }

    /**
     * The Ministry of Truth API client
     *
     * @var ClientInterface
     */
    private $motClient;

    /**
     * Holds context of security parameters for building requests to the Ministry of Truth API endpoint
     *
     * @var CredentialsStorageInterface
     */
    private $credentialsStorage;

    /**
     * Converts data from external format to local dto
     *
     * @var DataConverter
     */
    private $dataConverter;

    /**
     * Options for the vacancy analyser
     *
     * Example:
     * ```
     * [
     *     'default_locale' => 'ru',
     *     'locales' => ['ru', 'ua', 'en']
     * ]
     * ```
     *
     * @var array
     */
    private $options;

    /**
     * MinistryOfTruth constructor.
     *
     * @param ClientInterface             $motClient          The Ministry of Truth API client
     * @param CredentialsStorageInterface $credentialsStorage Context of security parameters for building requests
     * @param DataConverter               $dataConverter      Converts data from external format to local dto
     * @param array                       $options            Options for the vacancy analyser
     */
    public function __construct(
        ClientInterface $motClient,
        CredentialsStorageInterface $credentialsStorage,
        DataConverter $dataConverter,
        array $options
    ) {
        $this->motClient          = $motClient;
        $this->credentialsStorage = $credentialsStorage;
        $this->dataConverter      = $dataConverter;

        $optionsResolver = new OptionsResolver();
        $this->configureOptions($optionsResolver);

        $this->options = $optionsResolver->resolve($options);
    }

    /**
     * {@inheritdoc}
     */
    public function analyse(Vacancy $vacancy): IndexDto
    {
        $localeDefault = $this->options['default_locale'];

        return $this->analyseByLocale($vacancy, $localeDefault);
    }

    /**
     * Returns a sanity index data for the vacancy with specified locale
     *
     * @param Vacancy $vacancy Vacancy entity
     * @param string  $locale  Locale for sanity data translation
     *
     * @return IndexDto
     */
    public function analyseByLocale(Vacancy $vacancy, string $locale): IndexDto
    {
        $indexRequest = new IndexRequest();
        $indexRequest->setLocale($locale);

        $authorizationToken = $this->credentialsStorage->getAuthorizationToken();
        $indexRequest->setAuthorizationToken($authorizationToken);

        $vacancyDescription = $vacancy->getText();
        $indexRequest->addContext(Context\Vacancy::DESCRIPTION, $vacancyDescription);

        // TODO: try-catch client layer exception
        $indexResponse = $this->motClient->index($indexRequest);
        $vacancyIndex  = $indexResponse->getIndex();

        $indexDto = $this->dataConverter->convertIndex($vacancyIndex);

        $vacancyId = $vacancy->getId();
        $indexDto->setVacancyId($vacancyId);

        return $indexDto;
    }

    /**
     * Performs options configuration for the vacancy analyser
     *
     * @param OptionsResolver $optionsResolver Validates options and merges them with default values
     *
     * @return void
     */
    protected function configureOptions(OptionsResolver $optionsResolver): void
    {
        // 'default_locale', 'locales'
        $this->configureLocaleOptions($optionsResolver);
    }
}
