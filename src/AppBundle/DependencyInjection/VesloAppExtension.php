<?php

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
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $locator = new FileLocator(__DIR__ . implode(DIRECTORY_SEPARATOR, ['', '..', 'Resources']));
        $loader  = new YamlFileLoader($container, $locator);

        $loader->load('config' . DIRECTORY_SEPARATOR . 'services.yml');

        $configuration = new Configuration();
        $config        = $this->processConfiguration($configuration, $configs);

        $httpClientConfig = [
            'headers' => [
                'user_agent' => $config['http_client']['headers']['user_agent'],
            ],
        ];
        $container->setParameter('veslo.app.http_client.config', $httpClientConfig);

        $amqpClientOptions = [
            'host'     => $config['amqp_client']['host'],
            'vhost'    => $config['amqp_client']['vhost'],
            'user'     => $config['amqp_client']['user'],
            'password' => $config['amqp_client']['password'],
        ];
        $container->setParameter('veslo.app.amqp_client.options', $amqpClientOptions);

        $container->setParameter(
            'veslo.app.workflow.vacancy_research.transitions.queue_prefix',
            $config['workflow']['vacancy_research']['transitions']['queue_prefix']
        );
    }
}
