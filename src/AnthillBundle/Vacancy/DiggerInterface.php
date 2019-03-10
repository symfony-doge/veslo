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
