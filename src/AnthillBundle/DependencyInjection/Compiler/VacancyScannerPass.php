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

namespace Veslo\AnthillBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;
use Veslo\AnthillBundle\Vacancy\ScannerInterface;

/**
 * Searches for registered vacancy scan services
 */
class VacancyScannerPass implements CompilerPassInterface
{
    /**
     * {@inheritdoc}
     */
    public function process(ContainerBuilder $container)
    {
        $scannerPoolDefinition = $container->getDefinition('veslo.anthill.vacancy.scanner_pool.unique_scanner_pool');

        $roadmapIds = $container->findTaggedServiceIds(ScannerInterface::TAG);

        foreach ($roadmapIds as $id => $tags) {
            foreach ($tags as $attributes) {
                $roadmapName = $attributes['roadmapName'];
                $reference   = new Reference($id);

                $scannerPoolDefinition->addMethodCall('addScanner', [$roadmapName, $reference]);
            }
        }
    }
}
