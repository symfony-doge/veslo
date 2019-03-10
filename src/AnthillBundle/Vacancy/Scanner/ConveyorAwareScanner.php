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

namespace Veslo\AnthillBundle\Vacancy\Scanner;

use Veslo\AnthillBundle\Dto\Vacancy\ConfigurableRoadmapDto;
use Veslo\AnthillBundle\Dto\Vacancy\LocationDto;
use Veslo\AnthillBundle\Dto\Vacancy\MultistrategicScannerDto;
use Veslo\AnthillBundle\Dto\Vacancy\Parser\ParsedDto;
use Veslo\AnthillBundle\Dto\Vacancy\ScannerDto;
use Veslo\AnthillBundle\Vacancy\ScannerInterface;

/**
 * Wrapper for Scanner that provides meta information for conveyor process
 */
class ConveyorAwareScanner
{
    /**
     * Actual scanner instance that performs lexical analysis
     *
     * @var ScannerInterface
     */
    private $scanner;

    /**
     * ConveyorAwareScanner constructor.
     *
     * @param ScannerInterface $scanner Actual scanner instance that performs lexical analysis
     */
    public function __construct(ScannerInterface $scanner)
    {
        $this->scanner = $scanner;
    }

    /**
     * Performs lexical analysis of data and returns vacancy-related parts with meta information for conveyor workflow
     *
     * @param LocationDto $location Context of vacancy location from internet
     *
     * @return ParsedDto
     */
    public function scan(LocationDto $location): ParsedDto
    {
        if ($this->scanner instanceof MultistrategicScanner) {
            $this->chooseStrategy($location);
        }

        $vacancyUrl = $location->getVacancyUrl();
        $rawDto     = $this->scanner->scan($vacancyUrl);

        $parsedDto = new ParsedDto();
        $parsedDto->setVacancy($rawDto);

        $scannerDto  = $this->scanner instanceof MultistrategicScanner
            ? new MultistrategicScannerDto()
            : new ScannerDto();

        $scannerName = substr(get_class($this->scanner), stripos(get_class($this->scanner), 'Scanner\\'));
        $scannerDto->setName($scannerName);

        if ($this->scanner instanceof MultistrategicScanner) {
            // TODO: strategy dto
//            $strategyDto = new StrategyDto();
//            $strategyDto->setName($strategyName);
//            $scannerDto->setStrategy($strategyDto);
        }

        $parsedDto->setScanner($scannerDto);
        $parsedDto->setLocation($location);

        return $parsedDto;
    }

    // TODO: descr
    private function chooseStrategy(LocationDto $location): void
    {
        // TODO: ensure availability

        /** @var ConfigurableRoadmapDto $roadmap */
        $roadmap = $location->getRoadmap();

        $searchStrategy     = $roadmap->getStrategy();
        $searchStrategyName = $searchStrategy->getName();

        $this->scanner->chooseStrategy($searchStrategyName);
    }
}
