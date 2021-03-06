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

namespace Veslo\SanityBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

/**
 * SanityBundle dependency injection extension.
 */
class VesloSanityExtension extends Extension
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
            'event_listeners.yml',
            'entity_repositories.yml',
            'entity_creators.yml',
            'workers.yml',
            'providers.yml',
            'normalizers.yml',
            'synchronizers.yml',
            'vacancy_analysers.yml',
        ];

        foreach ($configFiles as $configFile) {
            $loader->load('config' . DIRECTORY_SEPARATOR . $configFile);
        }

        $configuration = new Configuration();
        $config        = $this->processConfiguration($configuration, $configs);

        $motAnalyserOptions = [
            'default_locale' => $config['vacancy']['analyser']['default_locale'],
            'locales'        => $config['vacancy']['analyser']['locales'],
        ];

        $container->setParameter('veslo.sanity.vacancy.analyser.ministry_of_truth.options', $motAnalyserOptions);

        $container->setParameter(
            'veslo.sanity.vacancy.tag.group.provider.ministry_of_truth.options',
            $motAnalyserOptions
        );
    }
}
