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

use InvalidArgumentException;

/**
 * Should be implemented by service that accepts or rejects a decision about vacancy data at any of workflow places,
 * ex. should it be collected or not
 */
interface DecisionInterface
{
    /**
     * Returns positive if decision can be applied to specified vacancy data, negative otherwise
     *
     * @param object $context Context of vacancy data from website at any of workflow places
     *
     * @return bool
     *
     * @throws InvalidArgumentException If data part from context is not expected or invalid
     */
    public function isApplied(object $context): bool;

    /**
     * Returns a human-friendly description of that actions were performed for ensuring decision application
     * Can contain one or multiple sentences, each one describes a single checked condition
     *
     * @return iterable|string[]
     */
    public function getConditions(): iterable;
}
