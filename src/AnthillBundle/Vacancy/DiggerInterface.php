<?php

declare(strict_types=1);

namespace Veslo\AnthillBundle\Vacancy;

use Veslo\AnthillBundle\Vacancy\Roadmap\ConveyorAwareRoadmap;

/**
 * Should be implemented by service that performs vacancy searching by roadmaps
 */
interface DiggerInterface
{
    /**
     * Digs dung (vacancies) from internet by specified roadmap and attempts count
     *
     * @param ConveyorAwareRoadmap $roadmap    Provides URL of vacancies
     * @param int                  $iterations Digging iterations count, at least one expected
     *
     * @return int Successful dig iterations count
     */
    public function dig(ConveyorAwareRoadmap $roadmap, int $iterations = 1): int;
}
