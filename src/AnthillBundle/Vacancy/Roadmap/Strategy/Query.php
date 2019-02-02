<?php

namespace Veslo\AnthillBundle\Vacancy\Roadmap\Strategy;

use Veslo\AnthillBundle\Vacancy\ConfigurableRoadmapInterface;
use Veslo\AnthillBundle\Vacancy\Roadmap\StrategyInterface;

/**
 * Represents vacancy searching algorithm based on query with parameters
 */
class Query implements StrategyInterface
{
    /**
     * {@inheritdoc}
     */
    public function init(ConfigurableRoadmapInterface $roadmap): void
    {
        // TODO: Implement init() method.
    }

    /**
     * {@inheritdoc}
     */
    public function lookup(ConfigurableRoadmapInterface $roadmap): ?string
    {
        // TODO: Implement lookup() method.
    }

    /**
     * {@inheritdoc}
     */
    public function iterate(ConfigurableRoadmapInterface $roadmap): ?string
    {
        // TODO: Implement iterate() method.
    }
}
