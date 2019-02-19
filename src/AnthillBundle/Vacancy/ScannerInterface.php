<?php

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
