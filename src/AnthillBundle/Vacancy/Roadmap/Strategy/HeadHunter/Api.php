<?php

namespace Veslo\AnthillBundle\Vacancy\Roadmap\Strategy\HeadHunter;

use Veslo\AnthillBundle\Vacancy\Roadmap\ConfigurationInterface;
use Veslo\AnthillBundle\Vacancy\Roadmap\StrategyInterface;

/**
 * Represents vacancy searching algorithm for HeadHunter site based on public API
 * https://github.com/hhru/api/blob/master/docs/general.md
 */
class Api implements StrategyInterface
{
    /**
     * {@inheritdoc}
     */
    public function lookup(ConfigurationInterface $configuration): ?string
    {
        // TODO: Implement lookup() method.
    }

    /**
     * {@inheritdoc}
     */
    public function iterate(ConfigurationInterface $configuration): void
    {
        // TODO: Implement iterate() method.
    }
}
