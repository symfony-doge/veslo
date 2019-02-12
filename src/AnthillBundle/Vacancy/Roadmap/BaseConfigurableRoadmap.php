<?php

declare(strict_types=1);

namespace Veslo\AnthillBundle\Vacancy\Roadmap;

use Veslo\AnthillBundle\Vacancy\ConfigurableRoadmapInterface;

/**
 * Base implementation of configurable roadmap
 *
 * Note: this is just a recommendation how roadmap can be organized, RoadmapInterface can be implemented directly.
 * In most cases for new job website you need to configure a similar service with customized strategy and configuration.
 * Searching algorithm with website-specific code is encapsulated by StrategyInterface
 * Configuration storage, format and parameters for searching algorithm is encapsulated by ConfigurationInterface
 */
class BaseConfigurableRoadmap implements ConfigurableRoadmapInterface
{
    /**
     * Vacancy searching algorithm with website-specific code
     *
     * @var StrategyInterface
     */
    private $_strategy;

    /**
     * Configuration storage, format and parameters for searching algorithm
     *
     * @var ConfigurationInterface
     */
    private $_configuration;

    /**
     * BaseConfigurableRoadmap constructor.
     *
     * @param StrategyInterface      $strategy      Vacancy searching algorithm with website-specific code
     * @param ConfigurationInterface $configuration Configuration storage, format and parameters for searching algorithm
     */
    public function __construct(StrategyInterface $strategy, ConfigurationInterface $configuration)
    {
        $this->_strategy      = $strategy;
        $this->_configuration = $configuration;
    }

    /**
     * {@inheritdoc}
     */
    public function getStrategy(): StrategyInterface
    {
        return $this->_strategy;
    }

    /**
     * Returns roadmap configuration
     *
     * @return ConfigurationInterface
     */
    public function getConfiguration(): ConfigurationInterface
    {
        return $this->_configuration;
    }

    /**
     * {@inheritdoc}
     */
    public function hasNext(): bool
    {
        $configuration = $this->getConfiguration();

        $vacancyUrl = $this->_strategy->lookup($configuration);

        return !empty($vacancyUrl);
    }

    /**
     * {@inheritdoc}
     */
    public function next(): ?string
    {
        $configuration = $this->getConfiguration();

        $vacancyUrl = $this->_strategy->lookup($configuration);

        if (!empty($vacancyUrl)) {
            $this->_strategy->iterate($configuration);
        }

        return $vacancyUrl;
    }
}
