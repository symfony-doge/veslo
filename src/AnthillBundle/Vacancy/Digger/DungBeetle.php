<?php

declare(strict_types=1);

namespace Veslo\AnthillBundle\Vacancy\Digger;

use Exception;
use Psr\Log\LoggerInterface;
use Veslo\AnthillBundle\Vacancy\DiggerInterface;
use Veslo\AnthillBundle\Vacancy\Roadmap\ConveyorAwareRoadmap;
use Veslo\AppBundle\Workflow\Vacancy\PitInterface;

/**
 * Digs some dung (vacancies) from internet and sends to conveyor for processing
 * It wants some roadmap, so it can know what to dig and where to dig
 *
 *              _,=(_)=,_
 *           ,;`         `;,
 *          \\    (\^/)    //
 *           \\   <( )>   //
 *            <`-'`"""`'-`>
 *           _/           \_
 *         _(_\           /_)_
 *        /|` |`----.----`| `|\
 *        |/  |     |     |  \|
 *       />   |     |     |   <\
 *           _;     |     ;_
 *         <`_\     |     /_`>
 *         |\  `._  |  _.'  /|
 *         \|     `"""`    |/
 *          |\            /|
 *           \\          //
 *           /_>        <_\
 *
 * Note: if you are a staff member of any job website - don't stop this creature from doing its work,
 * angry owner will come and implement a proxy bypass. You've been warned.
 *
 * @see RoadmapInterface
 */
class DungBeetle implements DiggerInterface
{
    /**
     * Logger as it is
     *
     * @var LoggerInterface
     */
    private $logger;

    /**
     * Storage for fresh dung (vacancies)
     *
     * @var PitInterface
     */
    private $dungPit;

    /**
     * DungBeetle constructor.
     *
     * @param LoggerInterface $logger  Logger as it is
     * @param PitInterface    $dungPit Storage for digged dung (vacancies)
     */
    public function __construct(LoggerInterface $logger, PitInterface $dungPit)
    {
        $this->logger  = $logger;
        $this->dungPit = $dungPit;
    }

    /**
     * {@inheritdoc}
     */
    public function dig(ConveyorAwareRoadmap $roadmap, int $iterations = 1): int
    {
        $roadmapName = $roadmap->getName();

        $this->logger->debug('Digging started.', ['roadmap' => $roadmapName, 'iterations' => $iterations]);

        $successfulIterations = $this->digInternal($roadmap, $iterations);

        $this->logger->debug(
            'Digging completed.',
            [
                'roadmap'    => $roadmapName,
                'iterations' => $iterations,
                'successful' => $successfulIterations,
            ]
        );

        return $successfulIterations;
    }

    /**
     * Returns logger configured for dung beetle
     *
     * @return LoggerInterface
     */
    public function getLogger(): LoggerInterface
    {
        return $this->logger;
    }

    /**
     * Performs actual dung (vacancies) digging loop
     *
     * @param ConveyorAwareRoadmap $roadmap    Provides URL of vacancies
     * @param int                  $iterations Digging iterations count, at least one
     *
     * @return int Successful dig attempts count
     */
    private function digInternal(ConveyorAwareRoadmap $roadmap, int $iterations): int
    {
        $iterationRemains = max(1, $iterations);
        $iterationSuccess = 0;

        while ($iterationRemains > 0) {
            try {
                if ($this->digIteration($roadmap)) {
                    ++$iterationSuccess;
                }
            } catch (Exception $e) {
                $context = ['roadmap' => $roadmap->getName(), 'message' => $e->getMessage()];
                $this->logger->error('An error has been occurred during vacancy digging.', $context);
            }

            --$iterationRemains;
        }

        return $iterationSuccess;
    }

    /**
     * Returns positive if vacancy is successfully found by specified roadmap
     * Builds and sends a payload to conveyor for further processing according configured workflow
     *
     * @param ConveyorAwareRoadmap $roadmap Provides URL of vacancies
     *
     * @return bool Positive, if new vacancy has been successfully found, negative otherwise
     */
    private function digIteration(ConveyorAwareRoadmap $roadmap): bool
    {
        $roadmapName = $roadmap->getName();

        if (!$roadmap->hasNext()) {
            $this->logger->debug('No more vacancies.', ['roadmap' => $roadmapName]);

            return false;
        }

        $locationDto = $roadmap->next();

        $vacancyUrl = $locationDto->getVacancyUrl();
        $this->logger->info('Vacancy found.', ['roadmap' => $roadmapName, 'vacancyUrl' => $vacancyUrl]);

        $this->dungPit->offer($locationDto);

        return true;
    }
}
