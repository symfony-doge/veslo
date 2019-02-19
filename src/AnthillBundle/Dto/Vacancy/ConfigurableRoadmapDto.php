<?php

declare(strict_types=1);

namespace Veslo\AnthillBundle\Dto\Vacancy;

use Veslo\AnthillBundle\Dto\Vacancy\Roadmap\ConfigurationDto;
use Veslo\AnthillBundle\Dto\Vacancy\Roadmap\StrategyDto;

/**
 * Context of configurable roadmap by which the vacancy was found
 */
// inheritance used only for symfony serializer compatibility and cleaner communication with external services.
class ConfigurableRoadmapDto extends RoadmapDto
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
}
