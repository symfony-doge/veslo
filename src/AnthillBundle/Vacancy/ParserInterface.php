<?php

declare(strict_types=1);

namespace Veslo\AnthillBundle\Vacancy;

use Veslo\AnthillBundle\Dto\Vacancy\RawDto;

/**
 * Represents website-specific vacancy URL parsing algorithm
 */
interface ParserInterface
{
    /**
     * Returns parsed vacancy data
     *
     * @param string $vacancyUrl Vacancy URL
     *
     * @return RawDto
     */
    public function parse(string $vacancyUrl): RawDto;
}
