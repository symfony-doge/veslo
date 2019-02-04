<?php

namespace Veslo\AnthillBundle\Dto\Vacancy\Roadmap;

/**
 * Represents context of searching algorithm used by roadmap
 */
class StrategyDto
{
    /**
     * Searching algorithm name
     *
     * @var string
     */
    private $name;

    /**
     * Sets name for searching algorithm used by roadmap
     *
     * @param string $name Roadmap name
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * Returns name of searching algorithm used by roadmap
     *
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }
}
