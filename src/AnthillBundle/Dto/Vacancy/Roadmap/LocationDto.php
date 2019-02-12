<?php

declare(strict_types=1);

namespace Veslo\AnthillBundle\Dto\Vacancy\Roadmap;

/**
 * Context of vacancy location from internet
 */
class LocationDto
{
    /**
     * Vacancy URL for parsing
     * Response format depends on searching strategy
     *
     * @var string
     */
    private $vacancyUrl;

    /**
     * Context of roadmap by which the vacancy was found
     *
     * @var object
     */
    private $roadmap;

    /**
     * Sets vacancy URL
     *
     * @param string $vacancyUrl Vacancy URL for parsing
     *
     * @return void
     */
    public function setVacancyUrl(string $vacancyUrl): void
    {
        $this->vacancyUrl = $vacancyUrl;
    }

    /**
     * Returns vacancy URL
     *
     * @return string|null
     */
    public function getVacancyUrl(): ?string
    {
        return $this->vacancyUrl;
    }

    /**
     * Sets context of roadmap by which the vacancy was found
     *
     * @param object $roadmap Context of roadmap by which the vacancy was found
     *
     * @return void
     */
    public function setRoadmap(object $roadmap): void
    {
        $this->roadmap = $roadmap;
    }

    /**
     * Returns context of roadmap by which the vacancy was found
     *
     * @return object|null
     */
    public function getRoadmap(): ?object
    {
        return $this->roadmap;
    }
}
