<?php

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
        $roadmapStorageDefinition = $container->getDefinition('veslo.anthill.vacancy.roadmap_storage');

        $roadmapIds = $container->findTaggedServiceIds(RoadmapInterface::TAG);

        foreach ($roadmapIds as $id => $tags) {
            foreach ($tags as $attributes) {
                $roadmapName = $attributes['roadmapName'];
                $reference   = new Reference($id);

                $roadmapStorageDefinition->addMethodCall('addRoadmap', [$roadmapName, $reference]);
            }
        }
    }
}
