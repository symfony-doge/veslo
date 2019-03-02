<?php

declare(strict_types=1);

namespace Veslo\AnthillBundle\Dto\Vacancy;

use Veslo\AnthillBundle\Vacancy\Normalizer\RoadmapDtoDenormalizer;

/**
 * Context of roadmap by which the vacancy was found
 *
 * @see RoadmapDtoDenormalizer
 */
class RoadmapDto
{
    /**
     * Property with roadmap name
     *
     * @const string
     */
    public const PROPERTY_NAME = 'name';

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
     *
     * @return void
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * Returns roadmap name
     *
     * @return string|null
     */
    public function getName(): ?string
    {
        return $this->name;
    }
}
