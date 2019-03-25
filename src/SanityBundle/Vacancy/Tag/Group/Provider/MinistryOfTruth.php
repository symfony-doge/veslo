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

use Symfony\Component\Cache\Adapter\AdapterInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use SymfonyDoge\MinistryOfTruthClient\Bridge\Symfony\Credentials\StorageInterface as CredentialsStorageInterface;
use SymfonyDoge\MinistryOfTruthClient\ClientInterface;
use SymfonyDoge\MinistryOfTruthClient\Dto\Request\Tag\Group\Get\All\RequestDto as GetTagGroupsRequest;
use SymfonyDoge\MinistryOfTruthClient\Dto\Response\Tag\Group\ContentDto;
use Veslo\AppBundle\Integration\MinistryOfTruth\LocaleOptionsTrait;
use Veslo\SanityBundle\Vacancy\Tag\Group\Provider\DataConverter\MinistryOfTruth as DataConverter;
use Veslo\SanityBundle\Vacancy\Tag\Group\ProviderInterface;

/**
 * Uses the Ministry of Truth API to provide sanity tag groups for vacancy analysers
 */
class MinistryOfTruth implements ProviderInterface
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
     * Converts sanity tag groups data from external format to local data transfer objects
     *
     * @var DataConverter
     */
    private $dataConverter;

    /**
     * Holds result of last API call for current process
     *
     * @var AdapterInterface
     */
    private $cache;

    /**
     * Options for the sanity tags group provider
     *
     * Example:
     * ```
     * [
     *     'cache' => [
     *         'namespace' => 'veslo.sanity.vacancy.tag.group.provider.ministry_of_truth'
     *     ],
     *     'default_locale' => 'ru',
     *     'locale' => ['ru', 'ua', 'en']
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
     * @param CredentialsStorageInterface $credentialsStorage Holds context of security parameters for building requests
     * @param DataConverter               $dataConverter      Converts group data from external format to local DTOs
     * @param AdapterInterface            $cache              Holds result of last API call for current process
     * @param array                       $options            Options for the sanity tags group provider
     */
    public function __construct(
        ClientInterface $motClient,
        CredentialsStorageInterface $credentialsStorage,
        DataConverter $dataConverter,
        AdapterInterface $cache,
        array $options
    ) {
        $this->motClient          = $motClient;
        $this->credentialsStorage = $credentialsStorage;
        $this->dataConverter      = $dataConverter;
        $this->cache              = $cache;

        $optionsResolver = new OptionsResolver();
        $this->configureOptions($optionsResolver);

        $this->options = $optionsResolver->resolve($options);
    }

    /**
     * {@inheritdoc}
     */
    public function getTagGroups(): array
    {
        $cacheKey            = $this->options['cache']['namespace'] . __FUNCTION__;
        $groupDtoArrayCached = $this->cache->getItem($cacheKey);

        if ($groupDtoArrayCached->isHit()) {
            return $groupDtoArrayCached->get();
        }

        $tagGroups = $this->requestTagGroups();

        $groupDtoArray = $this->dataConverter->convertTagGroups($tagGroups);
        $groupDtoArrayCached->set($groupDtoArray);
        $this->cache->save($groupDtoArrayCached);

        return $groupDtoArray;
    }

    /**
     * Returns sanity tag groups in format of integration layer
     *
     * @return ContentDto[]
     */
    protected function requestTagGroups(): array
    {
        $request = new GetTagGroupsRequest();
        $request->setLocale($this->options['default_locale']);

        $authorizationToken = $this->credentialsStorage->getAuthorizationToken();
        $request->setAuthorizationToken($authorizationToken);

        $tagGroupsResponse = $this->motClient->getTagGroups($request);

        return $tagGroupsResponse->getTagGroups();
    }

    /**
     * Performs options configuration for sanity tag groups provider
     *
     * @param OptionsResolver $optionsResolver Validates options and merges them with default values
     *
     * @return void
     */
    protected function configureOptions(OptionsResolver $optionsResolver): void
    {
        if (method_exists(OptionsResolver::class, 'isNested')) {
            $optionsResolver->setDefault(
                'cache',
                function (OptionsResolver $cacheOptionsResolver) {
                    $cacheOptionsResolver->setDefault('namespace', md5(__CLASS__));
                }
            );
        // TODO: [upgrade] remove after migration to ~4.2 (options resolver prior to 4.2 doesn't support nested options)
        } else {
            $optionsResolver->setDefault('cache', ['namespace' => md5(__CLASS__)]);
        }

        // 'default_locale', 'locales'
        $this->configureLocaleOptions($optionsResolver);
    }
}
