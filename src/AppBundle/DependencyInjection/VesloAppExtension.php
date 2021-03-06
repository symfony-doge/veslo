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

namespace Veslo\AppBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

/**
 * AppBundle dependency injection extension.
 *
 * TODO: "blueprints" directory and custom loading instead of constants.
 */
class VesloAppExtension extends Extension
{
    /**
     * Blueprint is a configuration file with services that can be injected optionally (e.g. by specific condition)
     *
     * @const string
     */
    private const BLUEPRINT_DERECTORY = 'blueprints';

    /**
     * Service alias for http client at application level
     *
     * @const string
     */
    private const SERVICE_ALIAS_HTTP_CLIENT = 'veslo.app.http.client.base';

    /**
     * Service alias for a proxy locator
     *
     * @const string
     */
    private const SERVICE_ALIAS_PROXY_LOCATOR = 'veslo.app.http.proxy.locator';

    /**
     * Service id for verbose http client
     *
     * @const string
     */
    private const SERVICE_ID_HTTP_CLIENT_VERBOSE = 'veslo.app.http.client.verbose';

    /**
     * Service id of locator responsible for fetching proxy list if URI is used
     *
     * @const string
     */
    private const SERVICE_ID_PROXY_CHAIN_LOCATOR = 'veslo.app.http.proxy.locator_chain';

    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $locator = new FileLocator(__DIR__ . implode(DIRECTORY_SEPARATOR, ['', '..', 'Resources']));
        $loader  = new YamlFileLoader($container, $locator);

        $configFiles = [
            'twig_extensions.yml',
            'monolog.yml',
            'shared_cache.yml',
            'event_listeners.yml',
            'decoders.yml',
            'html.yml',
            'workflow.yml',
            'menu.yml',
            'proxy_locators.yml',
            'proxy_managers.yml',
            'clients.yml',
            'services.yml',
        ];

        foreach ($configFiles as $configFile) {
            $loader->load('config' . DIRECTORY_SEPARATOR . $configFile);
        }

        $configuration = new Configuration();
        $config        = $this->processConfiguration($configuration, $configs);

        $this->configureHttpClient($config['http']['client'], $container, $loader);
        $this->configureHttpProxy($config['http']['proxy'], $container, $loader);

        $amqpClientOptions = [
            'host'      => $config['amqp_client']['host'],
            'vhost'     => $config['amqp_client']['vhost'],
            'user'      => $config['amqp_client']['user'],
            'password'  => $config['amqp_client']['password'],
            'heartbeat' => $config['amqp_client']['heartbeat'],
        ];
        $container->setParameter('veslo.app.amqp_client.options', $amqpClientOptions);

        $container->setParameter(
            'veslo.app.workflow.vacancy_research.transitions.queue_prefix',
            $config['workflow']['vacancy_research']['transitions']['queue_prefix']
        );

        $container->setParameter(
            'veslo.app.monolog.formatter.line_formatter.format',
            $config['monolog']['formatter']['line']['format']
        );
    }

    /**
     * Configures global http client for application level
     *
     * @param array            $config    Http client node configuration
     * @param ContainerBuilder $container Container
     * @param LoaderInterface  $loader    Loads a configuration file with services
     *
     * @return void
     */
    private function configureHttpClient(array $config, ContainerBuilder $container, LoaderInterface $loader): void
    {
        $isVerboseHttpClient = filter_var($config['logging'], FILTER_VALIDATE_BOOLEAN);

        if ($isVerboseHttpClient) {
            $container->setAlias(self::SERVICE_ALIAS_HTTP_CLIENT, self::SERVICE_ID_HTTP_CLIENT_VERBOSE);
        }

        $httpClientConfig = [
            'headers' => [
                'User-Agent' => $config['headers']['user_agent'],
            ],
        ];
        $container->setParameter('veslo.app.http.client.config', $httpClientConfig);

        $isProxyEnabled = $config['proxy']['enabled'];

        // Http client stability options: proxy, fingerprint faking, etc.
        $httpClientStabilityOptions = [
            'proxy' => [
                'enabled' => $isProxyEnabled,
            ],
        ];
        $container->setParameter('veslo.app.http.client.stability_options', $httpClientStabilityOptions);

        // Activating HTTP client events (making a client event dispatcher referencing a real service).
        $clientEventDispatcherDefinitionFile = implode(
            DIRECTORY_SEPARATOR,
            ['config', self::BLUEPRINT_DERECTORY, 'client_event_dispatcher.yml']
        );
        $loader->load($clientEventDispatcherDefinitionFile);
    }

    /**
     * Configures proxy settings for http clients
     *
     * @param array            $config    Http proxy node configuration
     * @param ContainerBuilder $container Container
     * @param LoaderInterface  $loader    Loads a configuration file with services
     *
     * @return void
     */
    private function configureHttpProxy(array $config, ContainerBuilder $container, LoaderInterface $loader): void
    {
        $proxyLocatorUriOptions = [
            'uri'             => $config['dynamic']['fetch_uri'],
            'format'          => $config['dynamic']['format'],
            'decoder_context' => $config['dynamic']['decoder_context'],
        ];
        $container->setParameter('veslo.app.http.proxy.locator.uri_locator.options', $proxyLocatorUriOptions);

        if (!empty($proxyLocatorUriOptions['uri'])) {
            $container->setAlias(self::SERVICE_ALIAS_PROXY_LOCATOR, self::SERVICE_ID_PROXY_CHAIN_LOCATOR);
        }

        $container->setParameter('veslo.app.http.proxy.static_list', $config['static_list']);

        // Using an optional proxy cacher.
        if (!empty($config['dynamic']['cache']['enabled'])) {
            $proxyCacherOptions = [
                'key'      => $config['dynamic']['cache']['key'],
                'lifetime' => $config['dynamic']['cache']['lifetime'],
            ];
            $container->setParameter('veslo.app.http.proxy.cacher.options', $proxyCacherOptions);

            $proxyCacherDefinitionFile = implode(
                DIRECTORY_SEPARATOR,
                ['config', self::BLUEPRINT_DERECTORY, 'proxy_cacher.yml']
            );
            $loader->load($proxyCacherDefinitionFile);
        }
    }
}
