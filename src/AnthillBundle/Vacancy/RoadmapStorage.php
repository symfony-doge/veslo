<?php

namespace Veslo\AnthillBundle\Vacancy;

use Veslo\AnthillBundle\Exception\RoadmapNotFoundException;

/**
 * Aggregates all available roadmap services for vacancy parsing
 */
class RoadmapStorage
{
    /**
     * Roadmaps array indexed by name
     *
     * @var RoadmapInterface[]
     */
    private $roadmaps;

    /**
     * RoadmapStorage constructor.
     */
    public function __construct()
    {
        $this->roadmaps = [];
    }

    /**
     * Adds roadmap service in list of supported roadmaps
     *
     * @param string           $roadmapName Roadmap name
     * @param RoadmapInterface $roadmap     Service for roadmap support
     *
     * @return void
     */
    public function addRoadmap(string $roadmapName, RoadmapInterface $roadmap): void
    {
        $this->roadmaps[$roadmapName] = $roadmap;
    }

    /**
     * Returns roadmap by name if it's supported
     *
     * @param string $roadmapName Roadmap name
     *
     * @return RoadmapInterface
     *
     * @throws RoadmapNotFoundException
     */
    public function require(string $roadmapName): RoadmapInterface
    {
        if (!array_key_exists($roadmapName, $this->roadmaps)) {
            throw RoadmapNotFoundException::withName($roadmapName);
        }

        return $this->roadmaps[$roadmapName];
    }
}
