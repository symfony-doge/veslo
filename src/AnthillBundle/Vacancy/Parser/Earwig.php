<?php

declare(strict_types=1);

namespace Veslo\AnthillBundle\Vacancy\Parser;

use Exception;
use Psr\Log\LoggerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Veslo\AnthillBundle\Dto\Vacancy\LocationDto;
use Veslo\AnthillBundle\Vacancy\ScannerPool\ConveyorAwareScannerPool;
use Veslo\AppBundle\Workflow\Vacancy\PitInterface;

/**
 * TODO: ascii image and descr
 */
class Earwig
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
    private $chewedDungPit;

    /**
     * Earwig constructor.
     *
     * @param LoggerInterface          $logger        Logger as it is
     * @param NormalizerInterface      $normalizer    Converts an object into a set of arrays/scalars
     * @param ConveyorAwareScannerPool $scannerPool   Aggregates scanners for vacancy parsing in conveyor context
     * @param PitInterface             $chewedDungPit Storage for parsed vacancy from website-provider
     */
    public function __construct(
        LoggerInterface $logger,
        NormalizerInterface $normalizer,
        ConveyorAwareScannerPool $scannerPool,
        PitInterface $chewedDungPit
    ) {
        $this->logger        = $logger;
        $this->normalizer    = $normalizer;
        $this->scannerPool   = $scannerPool;
        $this->chewedDungPit = $chewedDungPit;
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

        $successfulIterations = $this->parseInternal($pit, $iterations);

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
     * Performs dung (vacancies) parsing loop
     *
     * @param PitInterface $pit        Vacancy URL storage
     * @param int          $iterations Parsing iterations count, at least one expected
     *
     * @return int Successful parse attempts count
     */
    private function parseInternal(PitInterface $pit, int $iterations = 1): int
    {
        $iterationRemains = max(1, $iterations);
        $iterationSuccess = 0;

        while ($iterationRemains > 0) {
            try {
                if ($this->parseIteration($pit)) {
                    ++$iterationSuccess;
                }
            } catch (Exception $e) {
                $context = ['source' => get_class($pit), 'message' => $e->getMessage()];
                $this->logger->error('An error has been occurred during vacancy parsing.', $context);
            }

            --$iterationRemains;
        }

        return $iterationSuccess;
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

        $this->chewedDungPit->offer($scanResult);

        return true;
    }
}