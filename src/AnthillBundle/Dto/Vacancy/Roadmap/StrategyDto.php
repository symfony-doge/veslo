<?php

declare(strict_types=1);

namespace Veslo\AnthillBundle\Dto\Vacancy\Roadmap;

/**
 * Context of searching algorithm used by roadmap
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
     *
     * @return void
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * Returns name of searching algorithm used by roadmap
     *
     * @return string|null
     */
    public function getName(): ?string
    {
        return $this->name;
    }
}
