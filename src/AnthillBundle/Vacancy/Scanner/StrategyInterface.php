<?php

declare(strict_types=1);

namespace Veslo\AnthillBundle\Vacancy\Scanner;

use Veslo\AnthillBundle\Dto\Vacancy\RawDto;
use Veslo\AnthillBundle\Exception\Vacancy\Scanner\Strategy\FetchFailedException;
use Veslo\AnthillBundle\Exception\Vacancy\Scanner\Strategy\TokenizationFailedException;

/**
 * Represents vacancy parsing algorithm for specific website-provider
 * Encapsulates logic of extracting an appropriate parts of vacancy from website-specific data
 *
 * Note: each strategy is an immutable algorithm version, you should not change it after deployment
 * due to conveyor processing; create a new strategy class if you need to implement modifications
 */
interface StrategyInterface
{
    /**
     * Executes an actual lookup algorithm using specified configuration and returns vacancy URL
     *
     * @param string $vacancyUrl Vacancy source URL
     *
     * @return string
     *
     * @throws FetchFailedException If an error occurred during vacancy data fetching from website
     */
    public function fetch(string $vacancyUrl): string;

    /**
     * Performs lexical analysis of specified vacancy data and returns appropriate parts
     * Implementation should properly fill RawDto with vacancy-related data
     *
     * @param string $data Source data for lexical analysis
     *
     * @return RawDto
     *
     * @throws TokenizationFailedException Will be thrown if, for example, some property path is not found in response
     */
    public function tokenize(string $data): RawDto;
}
