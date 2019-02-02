<?php

namespace Veslo\AnthillBundle\Vacancy\Roadmap;

use Veslo\AnthillBundle\Vacancy\ConfigurableRoadmapInterface;
use Veslo\AnthillBundle\Vacancy\Roadmap\Strategy\Query;

/**
 * Performs roadmap configuration by specified settings key and roadmap strategy
 */
class Configurator
{
    /**
     * Performs roadmap configuration by specified settings key and roadmap strategy
     *
     * @param ConfigurableRoadmapInterface $roadmap     Configurable roadmap instance
     * @param string                       $settingsKey Settings key in storage
     *
     * @return void
     */
    public function configure(ConfigurableRoadmapInterface $roadmap, string $settingsKey): void
    {
        $strategy = $roadmap->getStrategy();

        if ($strategy instanceof Query) {
            // $settings = $this->roadmapSettingsQueryRepository->require()
        }

        //$roadmap->setSettings($settings);
    }
}
