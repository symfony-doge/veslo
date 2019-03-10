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
use Veslo\AnthillBundle\Vacancy\RoadmapInterface;

/**
 * Searches for registered vacancy roadmap services
 */
class VacancyRoadmapPass implements CompilerPassInterface
{
    /**
     * {@inheritdoc}
     */
    public function process(ContainerBuilder $container)
    {
        $antQueenDefinition = $container->getDefinition('veslo.anthill.vacancy.ant_queen');

        $roadmapIds = $container->findTaggedServiceIds(RoadmapInterface::TAG);

        foreach ($roadmapIds as $id => $tags) {
            foreach ($tags as $attributes) {
                $roadmapName = $attributes['roadmapName'];
                $reference   = new Reference($id);

                $antQueenDefinition->addMethodCall('addRoadmap', [$roadmapName, $reference]);
            }
        }
    }
}
