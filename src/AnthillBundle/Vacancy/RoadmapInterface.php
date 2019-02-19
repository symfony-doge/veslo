<?php

declare(strict_types=1);

namespace Veslo\AnthillBundle\Vacancy;

use Veslo\AnthillBundle\Enum\Vacancy\Roadmap;

/**
 * Should be implemented by service that holds context and vacancy searching plan for specific website
 *
 * @see Roadmap
 */
interface RoadmapInterface
{
    /**
     * Service tag for aggregation in storage
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
