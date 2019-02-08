<?php

namespace Veslo\AnthillBundle\Vacancy\Roadmap\Strategy\HeadHunter\Api;

use Veslo\AnthillBundle\Vacancy\Roadmap\ConfigurationInterface;
use Veslo\AnthillBundle\Vacancy\Roadmap\StrategyInterface;

/**
 * Represents vacancy searching algorithm for HeadHunter site based on public API
 * https://github.com/hhru/api/blob/master/docs/general.md
 */
class Version20190204 implements StrategyInterface
{
    /**
     * {@inheritdoc}
     */
    public function lookup(ConfigurationInterface $configuration): ?string
    {
        // TODO: Implement lookup() method.

        $parameters = $configuration->getParameters();

        // if page > pages STOP.

        return null;
    }

    /**
     * {@inheritdoc}
     */
    public function iterate(ConfigurationInterface $configuration): void
    {
        // TODO: Implement iterate() method.

        // update "pages" (it can be lowered)
        // page = page + 1
    }
}
