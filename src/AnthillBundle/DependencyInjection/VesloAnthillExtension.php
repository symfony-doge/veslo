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

        $loader->load('config' . DIRECTORY_SEPARATOR . 'commands.yml');
        $loader->load('config' . DIRECTORY_SEPARATOR . 'controllers.yml');
        $loader->load('config' . DIRECTORY_SEPARATOR . 'event_listeners.yml');
        $loader->load('config' . DIRECTORY_SEPARATOR . 'entity_repositories.yml');
        $loader->load('config' . DIRECTORY_SEPARATOR . 'normalizers.yml');
        $loader->load('config' . DIRECTORY_SEPARATOR . 'fixtures.yml');
        $loader->load('config' . DIRECTORY_SEPARATOR . 'roadmaps.yml');
        $loader->load('config' . DIRECTORY_SEPARATOR . 'workers.yml');
        $loader->load('config' . DIRECTORY_SEPARATOR . 'services.yml');

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
            'veslo.anthill.vacancy.provider.options',
            [
                'per_page' => $config['vacancy']['provider']['per_page'],
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
