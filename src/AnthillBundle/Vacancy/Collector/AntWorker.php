<?php

declare(strict_types=1);

namespace Veslo\AnthillBundle\Vacancy\Collector;

use Exception;
use Psr\Log\LoggerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Veslo\AnthillBundle\Dto\Vacancy\Collector\AcceptanceDto;
use Veslo\AnthillBundle\Dto\Vacancy\Parser\ParsedDto;
use Veslo\AnthillBundle\Vacancy\Creator;
use Veslo\AnthillBundle\Vacancy\DecisionInterface;
use Veslo\AppBundle\Workflow\Vacancy\PitInterface;

/**
 * Collects dung (vacancies) from queue and persists in local storage
 *
 *              |     |
 *               \   /
 *                \_/
 *           __   /^\   __
 *          '  `. \_/ ,'  `
 *               \/ \/
 *          _,--./| |\.--._
 *       _,'   _.-\_/-._   `._
 *            |   / \   |
 *            |  /   \  |
 *           /   |   |   \
 *         -'    \___/    `-
 */
class AntWorker
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
     * Decision that should be checked for raw vacancy data if it can be collected or not
     *
     * @var DecisionInterface
     */
    private $decision;

    /**
     * Creates and persists a new vacancy instance in local storage
     *
     * @var Creator
     */
    private $vacancyCreator;

    /**
     * Storage for collected vacancy from website-provider
     * Destination place in which result of worker action will be persisted
     *
     * @var PitInterface
     */
    private $destination;

    /**
     * AntWorker constructor.
     *
     * @param LoggerInterface     $logger         Logger as it is
     * @param NormalizerInterface $normalizer     Converts an object into a set of arrays/scalars
     * @param DecisionInterface   $decision       Decision that should be applied to vacancy data for collecting
     * @param Creator             $vacancyCreator Creates and persists a new vacancy instance in local storage
     * @param PitInterface        $destination    Storage for collected vacancy from website-provider (workflow queue)
     */
    public function __construct(
        LoggerInterface $logger,
        NormalizerInterface $normalizer,
        DecisionInterface $decision,
        Creator $vacancyCreator,
        PitInterface $destination
    ) {
        $this->logger         = $logger;
        $this->normalizer     = $normalizer;
        $this->decision       = $decision;
        $this->vacancyCreator = $vacancyCreator;
        $this->destination    = $destination;
    }

    /**
     * Performs dung (vacancies) collecting from specified source {$iterations} times
     *
     * @param PitInterface $pit        Parsed vacancy data storage
     * @param int          $iterations Collecting iterations count, at least one expected
     *
     * @return int Successful collect iterations count
     */
    public function collect(PitInterface $pit, int $iterations = 1): int
    {
        $sourceName = get_class($pit);

        $this->logger->debug('Collecting started.', ['source' => $sourceName, 'iterations' => $iterations]);

        $successfulIterations = $this->collectInternal($pit, $iterations);

        $this->logger->debug(
            'Collecting completed.',
            [
                'source'     => $sourceName,
                'iterations' => $iterations,
                'successful' => $successfulIterations,
            ]
        );

        return $successfulIterations;
    }

    /**
     * Performs dung (vacancies) collecting loop
     *
     * @param PitInterface $pit        Parsed vacancy data storage
     * @param int          $iterations Collecting iterations count
     *
     * @return int Successful collect attempts count
     */
    private function collectInternal(PitInterface $pit, int $iterations): int
    {
        $iterationRemains = max(1, $iterations);
        $iterationSuccess = 0;

        while ($iterationRemains > 0) {
            try {
                if ($this->collectIteration($pit)) {
                    ++$iterationSuccess;
                }
            } catch (Exception $e) {
                $context = ['source' => get_class($pit), 'message' => $e->getMessage()];
                $this->logger->error('An error has been occurred during vacancy collecting.', $context);
            }

            --$iterationRemains;
        }

        return $iterationSuccess;
    }

    /**
     * Returns positive if vacancy is successfully collected
     * Builds and sends a payload to conveyor for further processing according configured workflow
     *
     * @param PitInterface $pit Parsed vacancy data storage
     *
     * @return bool Positive, if vacancy data has been successfully collected, negative otherwise
     */
    private function collectIteration(PitInterface $pit): bool
    {
        $sourceName = get_class($pit);

        /** @var ParsedDto $scanResult */
        $scanResult = $pit->poll();

        if (!$scanResult instanceof ParsedDto) {
            $this->logger->debug(
                'No more vacancies to collect.',
                [
                    'source' => $sourceName,
                    'input'  => gettype($scanResult),
                ]
            );

            return false;
        }

        $acceptance = new AcceptanceDto();
        $conditions = $this->decision->getConditions();
        $acceptance->setConditions($conditions);
        $acceptance->setData($scanResult);

        $acceptanceNormalized = $this->normalizer->normalize($acceptance);

        if (!$this->decision->isApplied($scanResult)) {
            $this->logger->debug('Vacancy rejected.', ['source' => $sourceName, 'denial' => $acceptanceNormalized]);

            return false;
        }

        $vacancy   = $this->vacancyCreator->createByParsedDto($scanResult);
        $vacancyId = $vacancy->getId();
        $acceptance->setVacancyId($vacancyId);

        $this->logger->info(
            'Vacancy accepted.',
            [
                'source'     => $sourceName,
                'vacancyId'  => $vacancyId,
                'acceptance' => $acceptanceNormalized,
            ]
        );

        $this->destination->offer($acceptance);

        return true;
    }
}
