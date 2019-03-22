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

namespace Veslo\SanityBundle\Vacancy\Tag\Group\Provider;

use Symfony\Component\OptionsResolver\Exception\InvalidOptionsException;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;
use SymfonyDoge\MinistryOfTruthClient\Bridge\Symfony\Credentials\StorageInterface as CredentialsStorageInterface;
use SymfonyDoge\MinistryOfTruthClient\ClientInterface;
use SymfonyDoge\MinistryOfTruthClient\Dto\Request\Tag\Group\Get\All\RequestDto as GetTagGroupsRequest;
use Veslo\SanityBundle\Vacancy\Tag\Group\ProviderInterface;

/**
 * Uses the Ministry of Truth API to provide sanity tag groups for vacancy analysers
 */
class MinistryOfTruth implements ProviderInterface
{
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
     * Options for the sanity tags group provider
     *
     * @var array
     */
    private $options;

    /**
     * MinistryOfTruth constructor.
     *
     * @param ClientInterface             $motClient          The Ministry of Truth API client
     * @param CredentialsStorageInterface $credentialsStorage Holds context of security parameters for building requests
     * @param array                       $options            Options for the sanity tags group provider
     */
    public function __construct(
        ClientInterface $motClient,
        CredentialsStorageInterface $credentialsStorage,
        array $options
    ) {
        $this->motClient          = $motClient;
        $this->credentialsStorage = $credentialsStorage;

        $optionsResolver = new OptionsResolver();
        $this->configureOptions($optionsResolver);

        $this->options = $optionsResolver->resolve($options);
    }

    /**
     * {@inheritdoc}
     */
    public function getTagGroups(): array
    {
        $request = new GetTagGroupsRequest();

        $authorizationToken = $this->credentialsStorage->getAuthorizationToken();
        $request->setAuthorizationToken($authorizationToken);

        // TODO: try-catch client layer exception
        $tagGroupsResponse = $this->motClient->getTagGroups($request);
        $tagGroups         = $tagGroupsResponse->getTagGroups();

        // TODO: data converter

        return [];
    }

    /**
     * Performs options configuration for the vacancy analyser
     *
     * @param OptionsResolver $optionsResolver Validates options and merges them with default values
     *
     * @return void
     *
     * TODO: extract to separate unit for DRY contract (see Veslo\SanityBundle\Vacancy\Analyser\MinistryOfTruth)
     * TODO: probably should be moved to motc bridge domain
     */
    protected function configureOptions(OptionsResolver $optionsResolver): void
    {
        $optionsResolver->setDefault('locales', null);

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

        $optionsResolver->setRequired(['default_locale', 'locales']);
    }
}
