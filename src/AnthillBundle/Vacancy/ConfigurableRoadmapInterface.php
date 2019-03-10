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

namespace Veslo\AnthillBundle\Vacancy;

use Veslo\AnthillBundle\Vacancy\Roadmap\ConfigurationInterface;
use Veslo\AnthillBundle\Vacancy\Roadmap\StrategyInterface;

/**
 * Should be implemented by roadmap service that can be configured with specific strategy and settings
 * Provides opportunity to customize vacancy searching algorithm, adds support for different searching criteria
 *
 * Configurable roadmaps can retrieve data (based on configuration) for traversing job sites with preferable strategy
 * For example:
 * - URL with vacancy identifier increment, ex. jobsite.ltd/category/(id, id+1, id+2)
 * - URL with search criteria, ex. jobsite.ltd/vacancies?text=$query&from=$from&to=$to ...
 */
interface ConfigurableRoadmapInterface extends RoadmapInterface
{
    /**
     * Returns strategy service that holds website-specific vacancy searching algorithm
     *
     * @return StrategyInterface
     */
    public function getStrategy(): StrategyInterface;

    /**
     * Returns configuration which will be used by searching strategy
     *
     * @return ConfigurationInterface
     */
    public function getConfiguration(): ConfigurationInterface;
}
