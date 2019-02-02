<?php

namespace Veslo\AnthillBundle\Vacancy\Roadmap;

use Veslo\AnthillBundle\Exception\RoadmapSettingsNotFoundException;
use Veslo\AnthillBundle\Vacancy\ConfigurableRoadmapInterface;

/**
 * Base implementation of configurable roadmap
 *
 * This is just a recommendation how roadmap can be organized, RoadmapInterface can be implemented directly
 * In most cases for new vacancy website you need to implement a related context service
 */
class BaseConfigurableRoadmap implements ConfigurableRoadmapInterface
{
    /**
     * Strategy for vacancy searching used by roadmap
     * Determines settings storage and algorithm
     *
     * @var StrategyInterface
     */
    private $_strategy;

    /**
     * Configuration settings for roadmap, ex. query criteria or vacancy identifier for next lookup
     *
     * @var SettingsInterface
     */
    private $_settings;

    /**
     * BaseConfigurableRoadmap constructor.
     *
     * @param StrategyInterface $strategy Vacancy searching algorithm
     */
    public function __construct(StrategyInterface $strategy)
    {
        $this->_strategy = $strategy;
        $this->_settings = null;
    }

    /**
     * {@inheritdoc}
     */
    public function setSettings(SettingsInterface $settings): void
    {
        $this->_settings = $settings;

        $this->_strategy->init($this);
    }

    /**
     * {@inheritdoc}
     */
    public function getStrategy(): StrategyInterface
    {
        return $this->_strategy;
    }

    /**
     * {@inheritdoc}
     */
    public function hasNext(): bool
    {
        $vacancyUrl = $this->_strategy->lookup($this);

        return !empty($vacancyUrl);
    }

    /**
     * {@inheritdoc}
     */
    public function next(): ?string
    {
        $vacancyUrl = $this->_strategy->lookup($this);

        if (!empty($vacancyUrl)) {
            $this->_strategy->iterate($this);
        }

        return $vacancyUrl;
    }

    /**
     * Returns roadmap settings
     *
     * @return SettingsInterface
     */
    public function getSettings(): SettingsInterface
    {
        $this->ensureSettings();

        return $this->_settings;
    }

    /**
     * Ensures that settings exists for roadmap, overwise an exception will be thrown
     *
     * @return void
     *
     * @throws RoadmapSettingsNotFoundException
     */
    private function ensureSettings(): void
    {
        if (!$this->_settings instanceof SettingsInterface) {
            throw new RoadmapSettingsNotFoundException();
        }
    }
}
