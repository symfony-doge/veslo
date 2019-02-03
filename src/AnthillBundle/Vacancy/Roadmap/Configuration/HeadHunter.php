<?php

namespace Veslo\AnthillBundle\Vacancy\Roadmap\Configuration;

use Veslo\AnthillBundle\Vacancy\Roadmap\ConfigurationInterface;
use Veslo\AnthillBundle\Vacancy\Roadmap\Strategy\HeadHunter\Api;

/**
 * Represents configuraton of vacancy searching algorithms for HeadHunter
 *
 * @see Api
 */
class HeadHunter implements ConfigurationInterface
{
    /**
     * {@inheritdoc}
     */
    public function apply(string $configurationKey): void
    {
        // TODO: Implement apply() method.
    }

    /**
     * {@inheritdoc}
     */
    public function getParameters()
    {
        // TODO: Implement getParameters() method.
    }

    /**
     * {@inheritdoc}
     */
    public function setParameters($parameters): void
    {
        // TODO: Implement setParameters() method.
    }

    /**
     * {@inheritdoc}
     */
    public function update(): void
    {
        // TODO: Implement update() method.
    }
}
