<?php

/*
 * This file is part of the Veslo project <https://github.com/symfony-doge/veslo>.
 *
 * (C) 2019 Pavel Petrov <itnelo@gmail.com>.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @license https://opensource.org/licenses/GPL-3.0 GPL-3.0
 */

declare(strict_types=1);

namespace Veslo\AnthillBundle\Dto\Vacancy;

use Veslo\AnthillBundle\Dto\Vacancy\Roadmap\ConfigurationDto;
use Veslo\AnthillBundle\Dto\Vacancy\Roadmap\StrategyDto;
use Veslo\AnthillBundle\Vacancy\Normalizer\RoadmapDtoDenormalizer;

/**
 * Context of configurable roadmap by which the vacancy was found
 *
 * @see RoadmapDtoDenormalizer
 */
// inheritance used only for symfony serializer compatibility and cleaner communication with external services.
class ConfigurableRoadmapDto extends RoadmapDto
{
    /**
     * Property with information about roadmap search strategy
     *
     * @const string
     */
    public const PROPERTY_STRATEGY = 'strategy';

    /**
     * Property with information about configuration for roadmap search strategy
     *
     * @const string
     */
    public const PROPERTY_CONFIGURATION = 'configuration';

    /**
     * Context of search algorithm
     *
     * @var StrategyDto
     */
    private $strategy;

    /**
     * Context of configuration for a search algorithm used by roadmap
     *
     * @var ConfigurationDto
     */
    private $configuration;

    /**
     * Sets context of search algorithm
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
     * Returns context of search algorithm
     *
     * @return StrategyDto|null
     */
    public function getStrategy(): ?StrategyDto
    {
        return $this->strategy;
    }

    /**
     * Sets context of configuration for search algorithm
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
     * Returns context of configuration for search algorithm
     *
     * @return ConfigurationDto|null
     */
    public function getConfiguration(): ?ConfigurationDto
    {
        return $this->configuration;
    }
}
