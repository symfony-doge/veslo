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

namespace Veslo\AnthillBundle\Enum\Fixture;

/**
 * Dictionary of fixture groups
 */
final class Group
{
    /**
     * Group name for roadmap configuration parameters used by vacancy searching algorithms
     *
     * @const string
     */
    public const ROADMAP_CONFIGURATION_PARAMETERS = 'roadmap.configuration.parameters';
}
