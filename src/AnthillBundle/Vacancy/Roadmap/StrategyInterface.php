<?php

namespace Veslo\AnthillBundle\Vacancy\Roadmap;

/**
 * Represents vacancy searching algorithm for specific website-provider
 */
interface StrategyInterface
{
    /**
     * Executes an actual lookup algorithm using specified configuration and returns vacancy URL
     *
     * @param ConfigurationInterface $configuration Roadmap configuration with parameters for searching algorithm
     *
     * @return string
     */
    public function lookup(ConfigurationInterface $configuration): ?string;

    /**
     * Moves roadmap cursor to the next vacancy that fits specified searching configuration
     *
     * @param ConfigurationInterface $configuration Roadmap configuration with parameters for searching algorithm
     *
     * @return void
     */
    public function iterate(ConfigurationInterface $configuration): void;
}
