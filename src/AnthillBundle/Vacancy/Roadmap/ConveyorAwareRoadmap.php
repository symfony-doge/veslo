<?php

declare(strict_types=1);

namespace Veslo\AnthillBundle\Vacancy\Roadmap;

use Veslo\AnthillBundle\Dto\Vacancy\ConfigurableRoadmapDto;
use Veslo\AnthillBundle\Dto\Vacancy\Roadmap\ConfigurationDto;
use Veslo\AnthillBundle\Dto\Vacancy\LocationDto;
use Veslo\AnthillBundle\Dto\Vacancy\Roadmap\StrategyDto;
use Veslo\AnthillBundle\Dto\Vacancy\RoadmapDto;
use Veslo\AnthillBundle\Vacancy\ConfigurableRoadmapInterface;
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
     * Returns name of wrapped roadmap
     *
     * @return string
     */
    public function getName(): string
    {
        $name = $this->name;

        if (!$this->roadmap instanceof ConfigurableRoadmapInterface) {
            return $name;
        }

        $configuration    = $this->roadmap->getConfiguration();
        $parameters       = $configuration->getParameters();
        $configurationKey = $parameters->getConfigurationKey();

        return "$name.$configurationKey";
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

        if ($this->roadmap instanceof ConfigurableRoadmapInterface) {
            $roadmapDto = $this->upgradeToConfigurableRoadmapDto($roadmapDto);
        }

        $locationDto = new LocationDto();
        $locationDto->setRoadmap($roadmapDto);
        $locationDto->setVacancyUrl($vacancyUrl);

        return $locationDto;
    }

    /**
     * Builds and returns configurable roadmap dto by specified base roadmap dto
     *
     * @param RoadmapDto $roadmapDto Base roadmap dto
     *
     * @return ConfigurableRoadmapDto
     */
    private function upgradeToConfigurableRoadmapDto(RoadmapDto $roadmapDto): ConfigurableRoadmapDto
    {
        $configurableRoadmapDto = new ConfigurableRoadmapDto($roadmapDto);

        $strategy     = $this->roadmap->getStrategy();
        $strategyName = substr(get_class($strategy), stripos(get_class($strategy), 'Strategy\\'));

        $strategyDto = new StrategyDto();
        $strategyDto->setName($strategyName);
        $configurableRoadmapDto->setStrategy($strategyDto);

        /** @var ConfigurationInterface $configuration */
        $configuration = $this->roadmap->getConfiguration();

        $parameters       = $configuration->getParameters();
        $configurationKey = $parameters->getConfigurationKey();

        $configurationDto = new ConfigurationDto();
        $configurationDto->setKey($configurationKey);
        $configurableRoadmapDto->setConfiguration($configurationDto);

        return $configurableRoadmapDto;
    }
}
