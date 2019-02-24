<?php

declare(strict_types=1);

namespace Veslo\AnthillBundle\Vacancy;

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
     */
    public function isApplied(object $context): bool;

    /**
     * Returns a human-friendly description of that actions were performed for ensuring decision application
     * Can contain one or multiple sentences, each one describes a single checked condition
     *
     * @return string[]
     */
    public function getConditions(): array;
}
