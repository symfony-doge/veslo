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

namespace Veslo\AnthillBundle\Vacancy\Parser;

use Closure;
use Psr\Log\LoggerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Veslo\AnthillBundle\Dto\Vacancy\LocationDto;
use Veslo\AnthillBundle\Vacancy\ScannerPool\ConveyorAwareScannerPool;
use Veslo\AppBundle\Workflow\Vacancy\PitInterface;
use Veslo\AppBundle\Workflow\Vacancy\Worker\Iteration;
use Veslo\AppBundle\Workflow\Vacancy\WorkerInterface;

/**
 * TODO: ascii image and descr
 */
class Earwig implements WorkerInterface
{
    /**
     * Logger as it is
     *
     * @var LoggerInterface
     */
    private $logger;

    /**
     * Converts an object into a set of arrays/scalars
     *
     * @var NormalizerInterface
     */
    private $normalizer;

    /**
     * Aggregates scanners for vacancy parsing in context of workflow conveyor
     *
     * @var ConveyorAwareScannerPool
     */
    private $scannerPool;

    /**
     * Storage for parsed vacancy from website-provider
     * Destination place in which result of worker action will be persisted
     *
     * @var PitInterface
     */
    private $destination;

    /**
     * Earwig constructor.
     *
     * @param LoggerInterface          $logger      Logger as it is
     * @param NormalizerInterface      $normalizer  Converts an object into a set of arrays/scalars
     * @param ConveyorAwareScannerPool $scannerPool Aggregates scanners for vacancy parsing in conveyor context
     * @param PitInterface             $destination Storage for parsed vacancy from website-provider
     */
    public function __construct(
        LoggerInterface $logger,
        NormalizerInterface $normalizer,
        ConveyorAwareScannerPool $scannerPool,
        PitInterface $destination
    ) {
        $this->logger      = $logger;
        $this->normalizer  = $normalizer;
        $this->scannerPool = $scannerPool;
        $this->destination = $destination;
    }

    /**
     * Performs dung (vacancies) parsing by specified source and attempts count
     *
     * @param PitInterface $pit        Vacancy URL storage
     * @param int          $iterations Parsing iterations count, at least one expected
     *
     * @return int Successful parse iterations count
     */
    public function parse(PitInterface $pit, int $iterations = 1): int
    {
        $sourceName = get_class($pit);

        $this->logger->debug('Parsing started.', ['source' => $sourceName, 'iterations' => $iterations]);

        $iteration     = Closure::fromCallable([$this, 'parseIteration']);
        $iterationLoop = new Iteration\Loop($this, $iteration, 'An error has been occurred during vacancy parsing.');

        $successfulIterations = $iterationLoop->execute($pit, $iterations);

        $this->logger->debug(
            'Parsing completed.',
            [
                'source'     => $sourceName,
                'iterations' => $iterations,
                'successful' => $successfulIterations,
            ]
        );

        return $successfulIterations;
    }

    /**
     * {@inheritdoc}
     */
    public function getLogger(): ?LoggerInterface
    {
        return $this->logger;
    }

    /**
     * Returns positive if vacancy is successfully parsed
     * Builds and sends a payload to conveyor for further processing according configured workflow
     *
     * @param PitInterface $pit Vacancy URL storage
     *
     * @return bool Positive, if vacancy data has been successfully parsed, negative otherwise
     */
    private function parseIteration(PitInterface $pit): bool
    {
        $sourceName = get_class($pit);

        /** @var LocationDto $location */
        $location = $pit->poll();

        if (!$location instanceof LocationDto) {
            $this->logger->debug(
                'No more vacancies to parse.',
                [
                    'source' => $sourceName,
                    'input'  => gettype($location),
                ]
            );

            return false;
        }

        $scanner = $this->scannerPool->requireByLocation($location);

        $scanResult = $scanner->scan($location);

        $scanResultNormalized = $this->normalizer->normalize($scanResult);
        $this->logger->info('Vacancy parsed.', ['source' => $sourceName, 'data' => $scanResultNormalized]);

        $this->destination->offer($scanResult);

        return true;
    }
}
