<?php

namespace Veslo\AnthillBundle\Vacancy\Roadmap;

use Veslo\AnthillBundle\Vacancy\ConfigurableRoadmapInterface;

/**
 * Represents vacancy searching algorithm
 *
 * @see Strategy
 */
interface StrategyInterface
{
    /**
     * Performs preparatory actions before roadmap traversing
     *
     * @param ConfigurableRoadmapInterface $roadmap Roadmap for initialization
     *
     * @return void
     */
    public function init(ConfigurableRoadmapInterface $roadmap): void;

    /**
     * Executes an actual lookup algorithm for specified roadmap and returns vacancy URL
     *
     * @param ConfigurableRoadmapInterface $roadmap Roadmap in which lookup should be performed
     *
     * @return string
     */
    public function lookup(ConfigurableRoadmapInterface $roadmap): ?string;

    /**
     * Moves roadmap cursor to the next vacancy
     *
     * @param ConfigurableRoadmapInterface $roadmap Roadmap in which iteration should be performed
     *
     * @return string
     */
    public function iterate(ConfigurableRoadmapInterface $roadmap): ?string;
}
