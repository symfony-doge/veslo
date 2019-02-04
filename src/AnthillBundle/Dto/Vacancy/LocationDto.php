<?php

namespace Veslo\AnthillBundle\Dto\Vacancy;

/**
 * Represents context of vacancy location from internet
 */
class LocationDto
{
    /**
     * Represents context of roadmap by which the vacancy was found
     *
     * @var RoadmapDto
     */
    public $roadmap;

    /**
     * Vacancy URL
     *
     * @var string
     */
    public $vacancyUrl;
}
