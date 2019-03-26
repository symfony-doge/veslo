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
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

/**
 * AppBundle dependency injection extension.
 */
class VesloAppExtension extends Extension
{
    /**
     * Service alias for http client at application level
     *
     * @const string
     */
    private const SERVICE_ALIAS_HTTP_CLIENT = 'veslo.app.http.client.base';
    /**
     * Service id for silent http client
     *
     * @const string
     */
    private const SERVICE_ID_HTTP_CLIENT_SILENT = 'veslo.app.http.client.silent';
    /**
     * Service id for verbose http client
     *
     * @const string
     */
    private const SERVICE_ID_HTTP_CLIENT_VERBOSE = 'veslo.app.http.client.verbose';

    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $locator = new FileLocator(__DIR__ . implode(DIRECTORY_SEPARATOR, ['', '..', 'Resources']));
        $loader  = new YamlFileLoader($container, $locator);

        $configFiles = [
            'twig_extensions.yml',
            'html.yml',
            'workflow.yml',
            'menu.yml',
            'proxy.yml',
            'clients.yml',
            'services.yml',
        ];

        foreach ($configFiles as $configFile) {
            $loader->load('config' . DIRECTORY_SEPARATOR . $configFile);
        }

        $configuration = new Configuration();
        $config        = $this->processConfiguration($configuration, $configs);

        $this->configureHttpClient($config['http']['client'], $container);

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
    }

    /**
     * Configures global http client for application level
     *
     * @param array            $config    Http client node configuration
     * @param ContainerBuilder $container Container
     *
     * @return void
     */
    private function configureHttpClient(array $config, ContainerBuilder $container): void
    {
        $isVerboseHttpClient = filter_var($config['logging'], FILTER_VALIDATE_BOOLEAN);

        $httpClientServiceId = $isVerboseHttpClient
            ? self::SERVICE_ID_HTTP_CLIENT_VERBOSE
            : self::SERVICE_ID_HTTP_CLIENT_SILENT;

        $container->setAlias(self::SERVICE_ALIAS_HTTP_CLIENT, $httpClientServiceId);

        $httpClientConfig = [
            'headers' => [
                'User-Agent' => $config['headers']['user_agent'],
            ],
        ];

        $container->setParameter('veslo.app.http.client.config', $httpClientConfig);

        // Http client stability options: proxy, fingerprint faking, etc.
        $httpClientStabilityOptions = [
            'proxy' => [
                'enabled' => $config['proxy']['enabled'],
            ],
        ];

        $container->setParameter('veslo.app.http.client.stability_options', $httpClientStabilityOptions);
        $container->setParameter('veslo.app.http.client.proxy.static_list', $config['proxy']['static_list']);
    }
}
