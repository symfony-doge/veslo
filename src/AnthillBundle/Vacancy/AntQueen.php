<?php

namespace Veslo\AnthillBundle\Vacancy;

use Veslo\AnthillBundle\Exception\RoadmapNotFoundException;

/**
 * Aggregates all available roadmap services for vacancy parsing
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
     * Returns roadmap by name if it's supported
     *
     * @param string $roadmapName Roadmap name
     * @param string $settingsKey Settings key, roadmap should implement configurable interface to use this feature
     *
     * @return RoadmapInterface
     *
     * @throws RoadmapNotFoundException
     */
    public function requireRoadmap(string $roadmapName, ?string $settingsKey = null): RoadmapInterface
    {
        if (!array_key_exists($roadmapName, $this->_roadmaps)) {
            throw RoadmapNotFoundException::withName($roadmapName);
        }

        $roadmap = $this->_roadmaps[$roadmapName];

        if (!empty($settingsKey)) {
            $this->_roadmapConfigurator->configure($roadmap, $settingsKey);
        }

        return $roadmap;
    }
}
