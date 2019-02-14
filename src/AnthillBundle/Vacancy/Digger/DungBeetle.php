<?php

declare(strict_types=1);

namespace Veslo\AnthillBundle\Vacancy\Digger;

use Exception;
use Psr\Log\LoggerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Veslo\AnthillBundle\Vacancy\DiggerInterface;
use Veslo\AnthillBundle\Vacancy\Roadmap\ConveyorAwareRoadmap;

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
     * Normalizes an object into a set of arrays/scalars
     *
     * @var NormalizerInterface
     */
    private $normalizer;

    /**
     * DungBeetle constructor.
     *
     * @param LoggerInterface     $logger     Logger as it is
     * @param NormalizerInterface $normalizer Normalizes an object into a set of arrays/scalars
     */
    public function __construct(LoggerInterface $logger, NormalizerInterface $normalizer)
    {
        $this->logger     = $logger;
        $this->normalizer = $normalizer;
    }

    /**
     * {@inheritdoc}
     */
    public function dig(ConveyorAwareRoadmap $roadmap, int $iterations = 1): int
    {
        $roadmapName = $roadmap->getName();

        $this->logger->info('Digging started.', ['roadmap' => $roadmapName, 'iterations' => $iterations]);

        $successfulIterations = $this->digInternal($roadmap, $iterations);

        $this->logger->info(
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
     *
     * @param ConveyorAwareRoadmap $roadmap Provides URL of vacancies
     *
     * @return bool
     */
    private function digIteration(ConveyorAwareRoadmap $roadmap): bool
    {
        $roadmapName = $roadmap->getName();

        if (!$roadmap->hasNext()) {
            $this->logger->info('No more vacancies.', ['roadmap' => $roadmapName]);

            return false;
        }

        $locationDto = $roadmap->next();

        // TODO: pass $locationDto to conveyor, it should encapsulate all date [de]normalization & queue logic
        $locationData = $this->normalizer->normalize($locationDto);

        try {
            // $this->conveyor->send(?) ($locationDto, queue(?))         // + rabbitmq, wrapper(?)
        } catch (Exception $e) {
            // TODO
        }

        $this->logger->info('Vacancy found.', ['roadmap' => $roadmapName, 'location' => $locationData]);

        return true;
    }
}
