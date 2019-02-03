<?php

namespace Veslo\AnthillBundle\Vacancy\Roadmap;

/**
 * Encapsulates HOW roadmap parameters are stored
 * PROVIDES that parameters to strategy service for analysis and editing
 *
 * Parameters represent CONTEXT of website and criteria for vacancy searching
 * ex. URL of website, vacancy identifier or category, publication date, etc.
 */
interface ConfigurationInterface
{
    /**
     * Retrieves and applies specific set of parameters for roadmap by unique key
     *
     * @param string $configurationKey Configuration key for parameters customization
     *
     * @return void
     */
    public function apply(string $configurationKey): void;

    /**
     * Returns set of parameters with format, specific for strategy that using it
     *
     * @return mixed
     */
    public function getParameters();

    /**
     * Returns set of parameters with format, specific for strategy that using it
     *
     * @param mixed $parameters Set of parameters with format, specific for strategy that using it
     *
     * @return void
     */
    public function setParameters($parameters): void;

    /**
     * Updates configuration for last applied key
     *
     * @return void
     */
    public function update(): void;
}
