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

use Symfony\Component\OptionsResolver\Exception\InvalidOptionsException;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;
use SymfonyDoge\MinistryOfTruthClient\ClientInterface;
use SymfonyDoge\MinistryOfTruthClient\Dto\Request\Index\RequestDto as IndexRequest;
use SymfonyDoge\MinistryOfTruthClient\Enum\Request\Index\Context;
use Veslo\AnthillBundle\Entity\Vacancy;
use Veslo\SanityBundle\Dto\Vacancy\IndexDto;
use Veslo\SanityBundle\Vacancy\Analyser\MinistryOfTruth\DataConverter;
use Veslo\SanityBundle\Vacancy\AnalyserInterface;

/**
 * Provides vacancy index, tags and other sanity data for a vacancy by external Ministry of Truth microservice
 */
class MinistryOfTruth implements AnalyserInterface
{
    /**
     * The Ministry of Truth API client
     *
     * @var ClientInterface
     */
    private $motClient;

    /**
     * Converts data from external format to local dto
     *
     * @var DataConverter
     */
    private $dataConverter;

    /**
     * Options for the vacancy analyser
     *
     * @var array
     */
    private $options;

    /**
     * MinistryOfTruth constructor.
     *
     * @param ClientInterface $motClient     The Ministry of Truth API client
     * @param DataConverter   $dataConverter Converts data from external format to local dto
     * @param array           $options       Options for the vacancy analyser
     */
    public function __construct(ClientInterface $motClient, DataConverter $dataConverter, array $options)
    {
        $this->motClient     = $motClient;
        $this->dataConverter = $dataConverter;

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
        $indexRequest->setAuthorizationToken($this->options['authorization_token']);
        $indexRequest->setLocale($locale);

        $vacancyDescription = $vacancy->getText();
        $indexRequest->addContext(Context\Vacancy::DESCRIPTION, $vacancyDescription);

        $indexResponse = $this->motClient->index($indexRequest);
        $vacancyIndex  = $indexResponse->getIndex();

        $indexDto = $this->dataConverter->convertIndex($vacancyIndex);

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
        $optionsResolver->setDefaults(['authorization_token' => null, 'locales' => null]);

        $optionsResolver->setDefault(
            'default_locale',
            function (Options $options, $previousValue) {
                if (!is_array($options['locales']) || !in_array($previousValue, $options['locales'])) {
                    throw new InvalidOptionsException(
                        'Value of the "default_locale" option is not present in locales array'
                    );
                }

                return $previousValue;
            }
        );

        $optionsResolver->setRequired(['authorization_token', 'default_locale', 'locales']);
    }
}
