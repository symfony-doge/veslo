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

namespace Veslo\AppBundle\Workflow\Vacancy;

/**
 * Should be implemented by service that represent queue storage for vacancy data at any state in workflow
 * Used by conveyor workers as data source (and destination for results)
 *
 * - This pit is full of dung (vacancies).
 */
interface PitInterface
{
    /**
     * Retrieves and removes next data transfer object for processing, or returns null if this pit is empty.
     *
     * @return object|null
     */
    public function poll(): ?object;

    /**
     * Enqueues new data transfer object that represents intermediate vacancy data state in workflow process
     *
     * @param object $dto Data transfer object that represents intermediate vacancy data state in workflow process
     *
     * @return bool Positive, if data transfer object is successfully enqueued, false otherwise (ex. overflow)
     */
    public function offer(object $dto): bool;
}
