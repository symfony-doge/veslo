<?php

namespace Veslo\AnthillBundle\Vacancy\Roadmap;

use Veslo\AnthillBundle\Dto\Vacancy\Roadmap\LocationDto;
use Veslo\AnthillBundle\Dto\Vacancy\RoadmapDto;
use Veslo\AnthillBundle\Vacancy\RoadmapInterface;

/**
 * Wrapper for roadmap that provides meta information for conveyor process
 */
class ConveyorAwareRoadmap
{
    /**
     * Actual roadmap instance that holds context and parsing plan for specific site
     *
     * @var RoadmapInterface
     */
    private $roadmap;

    /**
     * Wrapped roadmap name
     *
     * @var string
     */
    private $name;

    /**
     * ConveyorAwareRoadmap constructor.
     *
     * @param RoadmapInterface $roadmap Actual roadmap instance that holds context and parsing plan for specific site
     * @param string           $name    Wrapped roadmap name
     */
    public function __construct(RoadmapInterface $roadmap, string $name)
    {
        $this->roadmap = $roadmap;
        $this->name    = $name;
    }

    /**
     * Returns positive whenever roadmap has available vacancy for parsing
     *
     * @return bool
     *
     * @see RoadmapInterface::hasNext()
     */
    public function hasNext(): bool
    {
        return $this->roadmap->hasNext();
    }

    /**
     * Returns URL that contains vacancy for parsing with meta information about roadmap
     *
     * @return LocationDto|null
     *
     * @see RoadmapInterface::next()
     */
    public function next(): ?LocationDto
    {
        $vacancyUrl = $this->roadmap->next();

        if (empty($vacancyUrl)) {
            return null;
        }

        $roadmapDto = new RoadmapDto();
        $roadmapDto->setName($this->name);

        $locationDto = new LocationDto();
        $locationDto->setRoadmap($roadmapDto);
        $locationDto->setVacancyUrl($vacancyUrl);

        return $locationDto;
    }
}
