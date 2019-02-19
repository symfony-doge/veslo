<?php

declare(strict_types=1);

namespace Veslo\AnthillBundle\Dto\Vacancy\Parser;

use Veslo\AnthillBundle\Dto\Vacancy\LocationDto;
use Veslo\AnthillBundle\Dto\Vacancy\MultistrategicScannerDto;
use Veslo\AnthillBundle\Dto\Vacancy\RawDto;
use Veslo\AnthillBundle\Dto\Vacancy\ScannerDto;

/**
 * Context of parsed vacancy data for analysis
 *
 * This is just a raw data from specific website, it is not compatible with any project schema
 * Decisions which data will be stored in actual schema are performed at collecting stage
 */
class ParsedDto
{
    /**
     * Context of parsed vacancy data for analysis
     *
     * @var RawDto
     */
    private $vacancy;

    /**
     * Context of executed scanner
     *
     * @var MultistrategicScannerDto|ScannerDto
     */
    private $scanner;

    /**
     * Context of vacancy location from internet
     *
     * @var LocationDto
     */
    private $location;

    /**
     * Sets context of parsed vacancy data for analysis
     *
     * @param RawDto $vacancy Context of parsed vacancy data for analysis
     *
     * @return void
     */
    public function setVacancy(RawDto $vacancy): void
    {
        $this->vacancy = $vacancy;
    }

    /**
     * Returns context of parsed vacancy data for analysis
     *
     * @return RawDto|null
     */
    public function getVacancy(): ?RawDto
    {
        return $this->vacancy;
    }

    /**
     * Sets context of executed scanner
     *
     * @param ScannerDto $scanner Context of executed scanner
     *
     * @return void
     */
    public function setScanner(ScannerDto $scanner): void
    {
        $this->scanner = $scanner;
    }

    /**
     * Returns context of executed scanner
     *
     * @return ScannerDto|null
     */
    public function getScanner(): ?ScannerDto
    {
        return $this->scanner;
    }

    /**
     * Sets context of vacancy location from internet
     *
     * @param LocationDto $location Context of vacancy location from internet
     *
     * @return void
     */
    public function setLocation(LocationDto $location): void
    {
        $this->location = $location;
    }

    /**
     * Returns context of vacancy location from internet
     *
     * @return LocationDto|null
     */
    public function getLocation(): ?LocationDto
    {
        return $this->location;
    }
}
