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

namespace Veslo\AnthillBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

/**
 * AnthillBundle dependency injection extension.
 */
class VesloAnthillExtension extends Extension
{
    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $locator = new FileLocator(__DIR__ . implode(DIRECTORY_SEPARATOR, ['', '..', 'Resources']));
        $loader  = new YamlFileLoader($container, $locator);

        $configFiles = [
            'commands.yml',
            'controllers.yml',
            'event_listeners.yml',
            'entity_repositories.yml',
            'entity_creators.yml',
            'providers.yml',
            'normalizers.yml',
            'fixtures.yml',
            'roadmaps.yml',
            'scanner_pools.yml',
            'workers.yml',
            'queues.yml',
            'menu.yml',
            'services.yml',
        ];

        foreach ($configFiles as $configFile) {
            $loader->load('config' . DIRECTORY_SEPARATOR . $configFile);
        }

        $configuration = new Configuration();
        $config        = $this->processConfiguration($configuration, $configs);

        // Digging parameters.
        $container->setParameter(
            'veslo.anthill.vacancy.digging.roadmap.attempts_until_pause',
            $config['vacancy']['digging']['roadmap']['attempts_until_pause']
        );
        $container->setParameter(
            'veslo.anthill.vacancy.digging.roadmap.pause_duration',
            $config['vacancy']['digging']['roadmap']['pause_duration']
        );

        // Provider options.
        $container->setParameter(
            'veslo.anthill.vacancy.provider.journal.options',
            [
                'per_page'                   => $config['vacancy']['provider']['journal']['per_page'],
                'max_days_after_publication' => $config['vacancy']['provider']['journal']['max_days_after_publication'],
            ]
        );
        $container->setParameter(
            'veslo.anthill.vacancy.provider.archive.options',
            [
                'per_page'                   => $config['vacancy']['provider']['archive']['per_page'],
                'min_days_after_publication' => $config['vacancy']['provider']['archive']['min_days_after_publication'],
            ]
        );

        // Vacancy repository options.
        $container->setParameter(
            'veslo.anthill.vacancy_repository.options',
            [
                'cache_result_namespace' => $config['vacancy']['repository']['cache_result_namespace'],
                'cache_result_lifetime'  => $config['vacancy']['repository']['cache_result_lifetime'],
            ]
        );
    }
}
