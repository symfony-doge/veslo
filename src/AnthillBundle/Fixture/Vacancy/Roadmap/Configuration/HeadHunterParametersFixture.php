<?php

/*
 * This file is part of the Veslo project <https://github.com/symfony-doge/veslo>.
 *
 * (C) 2019 Pavel Petrov <itnelo@gmail.com>.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @license https://opensource.org/licenses/GPL-3.0 GPL-3.0
 */

declare(strict_types=1);

namespace Veslo\AnthillBundle\Fixture\Vacancy\Roadmap\Configuration;

use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Veslo\AnthillBundle\Enum\Fixture\Group as AnthillGroup;
use Veslo\AppBundle\Enum\Fixture\Group as ApplicationGroup;
use Veslo\AppBundle\Fixture\FileFixture;

/**
 * Configuration parameters fixture for HeadHunter vacancy search algorithms
 */
class HeadHunterParametersFixture extends FileFixture implements FixtureGroupInterface
{
    /**
     * {@inheritdoc}
     */
    public static function getGroups(): array
    {
        return [
            ApplicationGroup::PRODUCTION,
            AnthillGroup::ROADMAP_CONFIGURATION_PARAMETERS,
        ];
    }
}
