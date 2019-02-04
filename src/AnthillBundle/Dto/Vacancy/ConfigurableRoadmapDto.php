<?php

namespace Veslo\AnthillBundle\Dto\Vacancy;

/**
 * Represents context of roadmap by which the vacancy was found
 */
class ConfigurableRoadmapDto extends RoadmapDto
{
    /**
     * Roadmap name
     *
     * @var string
     */
    private $strategy;

    /**
     * Roadmap name
     *
     * @var string
     */
    private $configuration;
}
