<?php

namespace Veslo\AnthillBundle\Dto\Vacancy;

/**
 * Represents context of roadmap by which the vacancy was found
 */
class RoadmapDto
{
    /**
     * Roadmap name
     *
     * @var string
     */
    private $name;

    /**
     * Sets roadmap name
     *
     * @param string $name Roadmap name
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * Returns roadmap name
     *
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }
}
