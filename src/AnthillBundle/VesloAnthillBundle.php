<?php

namespace Veslo\AnthillBundle;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;
use Veslo\AnthillBundle\DependencyInjection\Compiler\VacancyRoadmapPass;

/**
 * Anthill bundle.
 */
class VesloAnthillBundle extends Bundle
{
    /**
     * {@inheritdoc}
     */
    public function build(ContainerBuilder $container)
    {
        parent::build($container);

        $container->addCompilerPass(new VacancyRoadmapPass());
    }
}
