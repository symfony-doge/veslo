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

namespace Veslo\AnthillBundle\Vacancy\Roadmap;

use Veslo\AnthillBundle\Exception\Vacancy\Roadmap\Strategy\LookupFailedException;

/**
 * Represents vacancy searching algorithm for specific website-provider
 *
 * Note: each strategy is an immutable algorithm version, you should not change it after deployment
 * due to conveyor processing; create a new strategy class if you need to implement modifications
 */
interface StrategyInterface
{
    /**
     * Executes an actual lookup algorithm using specified configuration and returns vacancy URL
     *
     * @param ConfigurationInterface $configuration Roadmap configuration with parameters for searching algorithm
     *
     * @return string|null
     *
     * @throws LookupFailedException If an error occurred during vacancy lookup on website
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
