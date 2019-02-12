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

        $container->setParameter(
            'veslo.app.http_client.headers.user_agent',
            $config['http_client']['headers']['user_agent']
        );
    }
}
