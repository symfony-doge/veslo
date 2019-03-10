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

namespace Veslo\AnthillBundle\Vacancy\ScannerPool;

use Veslo\AnthillBundle\Dto\Vacancy\LocationDto;
use Veslo\AnthillBundle\Exception\Vacancy\ScannerNotFoundException;
use Veslo\AnthillBundle\Vacancy\Scanner\ConveyorAwareScanner;
use Veslo\AnthillBundle\Vacancy\ScannerInterface;

/**
 * Aggregates scanners for vacancy parsing in context of workflow conveyor
 */
class ConveyorAwareScannerPool
{
    /**
     * Actual scanner pool that manages scan services
     * Aggregates unique scan services which are exclusively binded to one compatible roadmap for vacancy URL parsing
     *
     * @var UniqueScannerPool
     */
    private $scannerPool;

    /**
     * ConveyorAwareScannerPool constructor.
     *
     * @param UniqueScannerPool $scannerPool Aggregates scan services for vacancy URL parsing
     */
    public function __construct(UniqueScannerPool $scannerPool)
    {
        $this->scannerPool = $scannerPool;
    }

    /**
     * Adds scanner service in list of available vacancy URL scanners for specified roadmap
     *
     * @param string           $roadmapName Roadmap name
     * @param ScannerInterface $scanner     Performs lexical analysis for vacancy data from website
     *
     * @return void
     */
    public function addScanner(string $roadmapName, ScannerInterface $scanner): void
    {
        $this->scannerPool->addScanner($roadmapName, $scanner);
    }

    /**
     * Returns scan service that supports vacancies from specified location
     *
     * @param LocationDto $location Context of vacancy location from internet
     *
     * @return ConveyorAwareScanner
     *
     * @throws ScannerNotFoundException
     */
    public function requireByLocation(LocationDto $location): ConveyorAwareScanner
    {
        $scanner = $this->scannerPool->requireByLocation($location);

        return new ConveyorAwareScanner($scanner);
    }
}
