<?php

declare(strict_types=1);

namespace Veslo\AnthillBundle\Vacancy\ScannerPool;

use Veslo\AnthillBundle\Dto\Vacancy\LocationDto;
use Veslo\AnthillBundle\Dto\Vacancy\RoadmapDto;
use Veslo\AnthillBundle\Exception\Vacancy\ScannerNotFoundException;
use Veslo\AnthillBundle\Vacancy\ScannerInterface;

/**
 * Aggregates unique scan services which will be exclusively binded to one compatible roadmap for vacancy URL parsing
 */
class UniqueScannerPool
{
    /**
     * Array of scanners indexed by name of compatible roadmap
     *
     * @var ScannerInterface[]
     */
    private $_scanners;

    /**
     * UniqueScannerPool constructor.
     */
    public function __construct()
    {
        $this->_scanners = [];
    }

    /**
     * Adds scanner service in list of available vacancy URL scanners for specified roadmap
     *
     * @param string           $compatibleRoadmapName Name of compatible roadmap for scanner registration
     * @param ScannerInterface $scanner               Performs lexical analysis for vacancy data from website
     *
     * @return void
     */
    public function addScanner(string $compatibleRoadmapName, ScannerInterface $scanner): void
    {
        $this->_scanners[$compatibleRoadmapName] = $scanner;
    }

    /**
     * Returns scan service that supports vacancies from specified location
     *
     * @param LocationDto $location Context of vacancy location from internet
     *
     * @return ScannerInterface
     *
     * @throws ScannerNotFoundException
     */
    public function requireByLocation(LocationDto $location): ScannerInterface
    {
        $roadmap = $location->getRoadmap();

        if (!$roadmap instanceof RoadmapDto) {
            throw ScannerNotFoundException::invalidLocation();
        }

        $compatibleRoadmapName = $roadmap->getName();

        return $this->requireByRoadmap($compatibleRoadmapName);
    }

    /**
     * Returns scan service that supports vacancies from specified roadmap
     *
     * @param string $roadmapName Roadmap name
     *
     * @return ScannerInterface
     *
     * @throws ScannerNotFoundException
     */
    public function requireByRoadmap(string $roadmapName): ScannerInterface
    {
        if (!array_key_exists($roadmapName, $this->_scanners)) {
            throw ScannerNotFoundException::withRoadmapName($roadmapName);
        }

        return $this->_scanners[$roadmapName];
    }
}
