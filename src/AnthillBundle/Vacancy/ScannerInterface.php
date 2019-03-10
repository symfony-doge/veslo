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

use Veslo\AnthillBundle\Dto\Vacancy\RawDto;

/**
 * Should be implemented by service that holds context and parsing plan for specific website
 */
interface ScannerInterface
{
    /**
     * Service tag for aggregation in pool
     *
     * @const string
     */
    public const TAG = 'veslo.anthill.vacancy.scanner';

    /**
     * Performs lexical analysis for vacancy data from website and returns vacancy-related parts
     *
     * @param string $vacancyUrl Source URL for fetching and lexical analysis
     *
     * @return RawDto
     */
    public function scan(string $vacancyUrl): RawDto;
}
