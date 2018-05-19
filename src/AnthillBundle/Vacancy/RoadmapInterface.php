<?php

namespace Veslo\AnthillBundle\Vacancy;

/**
 * Should be implemented by service that holds context and parsing plan for specific site
 */
interface RoadmapInterface
{
    /**
     * Returns positive whenever roadmap has available vacancy for parsing
     *
     * @return bool
     */
    public function hasNext(): bool;

    /**
     * Returns URL that contains vacancy for parsing
     *
     * @return string|null
     */
    public function next(): ?string;
}
