<?php

namespace Veslo\AnthillBundle\Dto\Vacancy\Roadmap;

use Veslo\AnthillBundle\Dto\Vacancy\RoadmapDto;

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
     * @var RoadmapDto
     */
    private $roadmap;

    /**
     * Sets vacancy URL
     *
     * @param string $vacancyUrl
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
     * @return string
     */
    public function getVacancyUrl(): string
    {
        return $this->vacancyUrl;
    }

    /**
     * Sets context of roadmap by which the vacancy was found
     *
     * @param RoadmapDto $roadmap
     *
     * @return void
     */
    public function setRoadmap(RoadmapDto $roadmap): void
    {
        $this->roadmap = $roadmap;
    }

    /**
     * Returns context of roadmap by which the vacancy was found
     *
     * @return RoadmapDto
     */
    public function getRoadmap(): RoadmapDto
    {
        return $this->roadmap;
    }
}
