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

namespace Veslo\SanityBundle\Vacancy\Indexer;

use Closure;
use Psr\Log\LoggerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Veslo\AnthillBundle\Dto\Vacancy\Collector\AcceptanceDto;
use Veslo\AnthillBundle\Entity\Repository\VacancyRepository;
use Veslo\AnthillBundle\Entity\Vacancy;
use Veslo\AppBundle\Workflow\Vacancy\PitInterface;
use Veslo\AppBundle\Workflow\Vacancy\Worker\Iteration;
use Veslo\AppBundle\Workflow\Vacancy\WorkerInterface;
use Veslo\SanityBundle\Vacancy\AnalyserInterface;

/**
 * Delivers a vacancy to the Ministry of Truth for analysis and sanity index calculation
 *
 *           .--.       .--.
 *       _  `    \     /    `  _
 *        `\.===. \.^./ .===./`
 *               \/`"`\/
 *            ,  |     |  ,
 *           / `\|`-.-'|/` \
 *          /    |  \  |    \
 *       .-' ,-'`|   ; |`'-, '-.
 *           |   |    \|   |
 *           |   |    ;|   |
 *           |   \    //   |
 *           |    `._//'   |
 *          .'             `.
 *       _,'                 `,_
 *       `                     `
 */
class Cockroach implements WorkerInterface
{
    /**
     * Logger
     *
     * @var LoggerInterface
     */
    private $logger;

    /**
     * Vacancy repository
     *
     * @var VacancyRepository
     */
    private $vacancyRepository;

    /**
     * Performs a contextual analysis of vacancy data
     *
     * @var AnalyserInterface
     */
    private $vacancyAnalyser;

    /**
     * Converts a sanity index data to array format for third-party services (e.g. logger)
     *
     * @var NormalizerInterface
     */
    private $sanityIndexNormalizer;

    /**
     * Cockroach constructor.
     *
     * @param LoggerInterface     $logger                Logger
     * @param VacancyRepository   $vacancyRepository     Vacancy repository
     * @param AnalyserInterface   $vacancyAnalyser       Performs a contextual analysis of vacancy data
     * @param NormalizerInterface $sanityIndexNormalizer Converts a sanity index data to array for third-party services
     */
    public function __construct(
        LoggerInterface $logger,
        VacancyRepository $vacancyRepository,
        AnalyserInterface $vacancyAnalyser,
        NormalizerInterface $sanityIndexNormalizer
    ) {
        $this->logger                = $logger;
        $this->vacancyRepository     = $vacancyRepository;
        $this->vacancyAnalyser       = $vacancyAnalyser;
        $this->sanityIndexNormalizer = $sanityIndexNormalizer;
    }

    /**
     * Performs vacancy indexation iteration
     *
     * @param PitInterface $pit        Vacancy indexation queue
     * @param int          $iterations Indexing iterations count, at least one expected
     *
     * @return int
     */
    public function deliver(PitInterface $pit, int $iterations = 1): int
    {
        $sourceName = get_class($pit);

        $this->logger->debug('Indexing started.', ['source' => $sourceName, 'iterations' => $iterations]);

        $iteration     = Closure::fromCallable([$this, 'deliverIteration']);
        $iterationLoop = new Iteration\Loop($this, $iteration, 'An error has been occurred during vacancy indexing.');

        $successfulIterations = $iterationLoop->execute($pit, $iterations);

        $this->logger->debug(
            'Indexing completed.',
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
     * Returns positive if vacancy is successfully indexed
     *
     * @param PitInterface $pit Vacancy indexation queue
     *
     * @return bool Positive, if vacancy data was successfully indexed, negative otherwise
     */
    private function deliverIteration(PitInterface $pit): bool
    {
        $sourceName = get_class($pit);

        /** @var AcceptanceDto $acceptance */
        $acceptance = $pit->poll();

        if (!$acceptance instanceof AcceptanceDto) {
            $this->logger->debug(
                'No more vacancies to index.',
                [
                    'source' => $sourceName,
                    'input'  => gettype($acceptance),
                ]
            );

            return false;
        }

        $vacancyId = $acceptance->getVacancyId();

        /** @var Vacancy $vacancy */
        $vacancy = $this->vacancyRepository->require($vacancyId);

        $sanityIndex = $this->vacancyAnalyser->analyse($vacancy);

        // todo: persist sanity data, sync tag groups / translations


        $sanityIndexNormalized = $this->sanityIndexNormalizer->normalize($sanityIndex);

        $this->logger->info(
            'Vacancy indexed.',
            [
                'source'    => $sourceName,
                'vacancyId' => $vacancyId,
                'index'     => $sanityIndexNormalized,
            ]
        );

        return true;
    }
}
