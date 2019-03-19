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

use Psr\Log\LoggerInterface;

/**
 * Should be implemented by a service that turns data from one state to another according to workflow
 */
interface WorkerInterface
{
    /**
     * Returns a service for logging events during logic execution
     *
     * @return LoggerInterface|null
     */
    public function getLogger(): ?LoggerInterface;
}
