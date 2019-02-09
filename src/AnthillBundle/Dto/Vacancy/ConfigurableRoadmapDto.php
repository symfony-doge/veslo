<?php

namespace Veslo\AnthillBundle\Dto\Vacancy;

use Veslo\AnthillBundle\Dto\Vacancy\Roadmap\ConfigurationDto;
use Veslo\AnthillBundle\Dto\Vacancy\Roadmap\StrategyDto;

/**
 * Context of configurable roadmap by which the vacancy was found
 */
class ConfigurableRoadmapDto
{
    /**
     * Context of searching algorithm
     *
     * @var StrategyDto
     */
    private $strategy;

    /**
     * Context of configuration for searching algorithm used by roadmap
     *
     * @var ConfigurationDto
     */
    private $configuration;

    /**
     * Base roadmap data
     *
     * @var RoadmapDto
     */
    private $roadmap;

    /**
     * Sets context of searching algorithm
     *
     * @param StrategyDto $strategy
     *
     * @return void
     */
    public function setStrategy(StrategyDto $strategy): void
    {
        $this->strategy = $strategy;
    }

    /**
     * Returns context of searching algorithm
     *
     * @return StrategyDto|null
     */
    public function getStrategy(): ?StrategyDto
    {
        return $this->strategy;
    }

    /**
     * Sets context of configuration for searching algorithm
     *
     * @param ConfigurationDto $configuration
     *
     * @return void
     */
    public function setConfiguration(ConfigurationDto $configuration): void
    {
        $this->configuration = $configuration;
    }

    /**
     * Returns context of configuration for searching algorithm
     *
     * @return ConfigurationDto|null
     */
    public function getConfiguration(): ?ConfigurationDto
    {
        return $this->configuration;
    }

    /**
     * Sets base roadmap data
     *
     * @param RoadmapDto $roadmap
     *
     * @return void
     */
    public function setRoadmap(RoadmapDto $roadmap): void
    {
        $this->roadmap = $roadmap;
    }

    /**
     * Returns base roadmap data
     *
     * @return RoadmapDto|null
     */
    public function getRoadmap(): ?RoadmapDto
    {
        return $this->roadmap;
    }
}
