<?php

namespace Veslo\AnthillBundle\Vacancy;

use Veslo\AnthillBundle\Vacancy\Roadmap\SettingsInterface;
use Veslo\AnthillBundle\Vacancy\Roadmap\StrategyInterface;

/**
 * Should be implemented by roadmap service that can be configured with specific settings and strategy
 * Provides opportunity to customize vacancy searching criteria
 *
 * Configurable roadmaps can retrieve data (based on settings) for traversing job sites with preferable strategy
 * For example:
 * - URL with vacancy identifier increment, ex. jobsite.ltd/category/(id, id+1, id+2)
 * - URL with search criteria, ex. jobsite.ltd/vacancies?text=$query&from=$from&to=$to ...
 */
interface ConfigurableRoadmapInterface extends RoadmapInterface
{
    /**
     * Returns vacancy searching strategy used by roadmap
     *
     * @return StrategyInterface
     */
    public function getStrategy(): StrategyInterface;

    /**
     * Returns configuration settings used by roadmap
     *
     * @return SettingsInterface
     */
    public function getSettings(): SettingsInterface;

    /**
     * Sets configuration settings for roadmap
     *
     * @param SettingsInterface $settings Configuration settings for roadmap
     *
     * @return void
     */
    public function setSettings(SettingsInterface $settings): void;
}
