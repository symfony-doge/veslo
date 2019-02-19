<?php

declare(strict_types=1);

namespace Veslo\AnthillBundle\Vacancy\Roadmap;

use Veslo\AnthillBundle\Exception\Vacancy\Roadmap\ConfigurationNotFoundException;
use Veslo\AnthillBundle\Vacancy\Roadmap\Configuration\ParametersInterface;

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
     * @return ParametersInterface
     *
     * @throws ConfigurationNotFoundException If configuration hasn't been properly applied
     */
    public function getParameters(): ParametersInterface;

    /**
     * Sets parameters with format, specific for strategy that using it
     *
     * @param ParametersInterface $parameters Set of parameters with format, specific for strategy that using it
     *
     * @return void
     *
     * @throws ConfigurationNotFoundException If configuration hasn't been properly applied
     */
    public function setParameters(ParametersInterface $parameters): void;

    /**
     * Saves configuration for last applied key
     *
     * @return void
     *
     * @throws ConfigurationNotFoundException If configuration hasn't been properly applied
     */
    public function save(): void;
}
