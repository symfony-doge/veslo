<?php

declare(strict_types=1);

namespace Veslo\AnthillBundle\Vacancy;

use Veslo\AnthillBundle\Exception\Roadmap\ConfigurationNotSupportedException;
use Veslo\AnthillBundle\Exception\RoadmapNotFoundException;
use Veslo\AnthillBundle\Vacancy\Roadmap\ConveyorAwareRoadmap;

/**
 * Aggregates and builds roadmaps for vacancy parsing
 * It means that each beetle should ask her what to do and how to do, yup
 *
 *              *
 *            * | *
 *           * \|/ *
 *      * * * \|O|/ * * *
 *       \o\o\o|O|o/o/o/
 *       (<><><>O<><><>)
 *        '==========='
 *            \.-./
 *           (o\^/o)  _   _   _     __
 *            ./ \.\ ( )-( )-( ) .-'  '-.
 *             {-} \(//  ||   \\/ (   )) '-.
 *                  //-__||__.-\\.       .-'
 *                 (/    ()     \)'-._.-'
 *                 ||    ||      \\
 *                 ('    ('      ')
 */
class AntQueen
{
    /**
     * Roadmaps array indexed by name
     *
     * @var RoadmapInterface[]
     */
    private $_roadmaps;

    /**
     * AntQueen constructor.
     */
    public function __construct()
    {
        $this->_roadmaps = [];
    }

    /**
     * Returns all available roadmaps for vacancy searching
     *
     * @return RoadmapInterface[]
     */
    public function getRoadmaps(): array
    {
        return $this->_roadmaps;
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
        $this->_roadmaps[$roadmapName] = $roadmap;
    }

    /**
     * Returns roadmap by name or throws an exception if roadmap is not supported
     *
     * @param string $roadmapName Roadmap name
     *
     * @return RoadmapInterface
     *
     * @throws RoadmapNotFoundException
     */
    public function requireRoadmap(string $roadmapName): RoadmapInterface
    {
        if (!array_key_exists($roadmapName, $this->_roadmaps)) {
            throw RoadmapNotFoundException::withName($roadmapName);
        }

        return $this->_roadmaps[$roadmapName];
    }

    /**
     * Builds roadmap for conveyor process
     *
     * @param string $roadmapName      Roadmap name
     * @param string $configurationKey A configuration key, roadmap should support configurable interface
     *
     * @return ConveyorAwareRoadmap
     *
     * @throws ConfigurationNotSupportedException
     */
    public function buildRoadmap(string $roadmapName, ?string $configurationKey = null): ConveyorAwareRoadmap
    {
        $roadmap = $this->requireRoadmap($roadmapName);

        if (!empty($configurationKey)) {
            if (!$roadmap instanceof ConfigurableRoadmapInterface) {
                throw ConfigurationNotSupportedException::withName($roadmapName);
            }

            $configuration = $roadmap->getConfiguration();
            $configuration->apply($configurationKey);
        }

        return new ConveyorAwareRoadmap($roadmap, $roadmapName);
    }
}
