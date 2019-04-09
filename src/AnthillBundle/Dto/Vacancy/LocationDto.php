<?php

/*
 * This file is part of the Veslo project <https://github.com/symfony-doge/veslo>.
 *
 * (C) 2019 Pavel Petrov <itnelo@gmail.com>.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @license https://opensource.org/licenses/GPL-3.0 GPL-3.0
 */

declare(strict_types=1);

namespace Veslo\AnthillBundle\Dto\Vacancy;

/**
 * Context of vacancy location from internet
 */
class LocationDto
{
    /**
     * Vacancy URL for parsing
     * Response format depends on search strategy
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
     * Returns vacancy URL
     *
     * @return string|null
     */
    public function getVacancyUrl(): ?string
    {
        return $this->vacancyUrl;
    }

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
     * Returns context of roadmap by which the vacancy was found
     *
     * @return RoadmapDto|null
     */
    public function getRoadmap(): ?RoadmapDto
    {
        return $this->roadmap;
    }

    /**
     * Sets context of roadmap by which the vacancy was found
     *
     * @param RoadmapDto $roadmap Context of roadmap by which the vacancy was found
     *
     * @return void
     */
    public function setRoadmap(RoadmapDto $roadmap): void
    {
        $this->roadmap = $roadmap;
    }
}
