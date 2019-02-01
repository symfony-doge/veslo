<?php

namespace Veslo\AnthillBundle\Vacancy;

/**
 * Should be implemented by service that holds context and parsing plan for specific site
 */
interface RoadmapInterface
{
    /**
     * Roadmap service tag for aggregation in storage
     *
     * @const string
     */
    public const TAG = 'veslo.anthill.vacancy.roadmap';

    /**
     * Returns positive whenever roadmap has available vacancy for parsing
     *
     * @return bool
     */
    public function hasNext(): bool;

    /**
     * Returns URL that contains vacancy for parsing
     * Should be guaranteed string if hasNext is positive
     * This method can potentially change internal cursor position, for availability checks use hasNext instead
     *
     * @return string|null
     */
    public function next(): ?string;
}
