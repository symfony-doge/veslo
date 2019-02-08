<?php

namespace Veslo\AnthillBundle\Vacancy;

use Exception;
use Psr\Log\LoggerInterface;
use Veslo\AnthillBundle\Vacancy\Roadmap\ConveyorAwareRoadmap;

/**
 * Digs some dung (vacancies) from internet and sends to conveyor for processing
 * He wants some roadmap, so he can know what to dig and where to dig
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
 * @see RoadmapInterface
 */
class DungBeetle
{
    /**
     * Logger
     *
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * DungBeetle constructor.
     *
     * @param LoggerInterface $logger Logger
     */
    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    /**
     * Performs dung (vacancies) digging iterations
     *
     * @param ConveyorAwareRoadmap $roadmap    Provides URL of vacancies
     * @param int                  $iterations Digging iterations count, at least one
     *
     * @return void
     */
    public function dig(ConveyorAwareRoadmap $roadmap, int $iterations = 1): void
    {
        $iterationRemains = max(1, $iterations);

        while ($iterationRemains > 0) {
            try {
                $this->digIteration($roadmap);
            } catch (Exception $e) {
                $context = ['message' => $e->getMessage()];

                $this->logger->log('error', 'An error has been occurred during vacancy digging.', $context);
            }

            --$iterationRemains;
        }

        $this->logger->log('info', 'Digging complete.', ['iterations' => $iterations]);
    }

    /**
     * Encapsulates digging algorithm
     *
     * @param ConveyorAwareRoadmap $roadmap Provides URL of vacancies
     *
     * @return void
     */
    private function digIteration(ConveyorAwareRoadmap $roadmap): void
    {
        // TODO: digging hard...
        sleep(1);

        if (!$roadmap->hasNext()) {
            $this->logger->log('info', 'No more vacancies to parse.', ['roadmap' => $roadmap]);

            return;
        }

        $locationDto = $roadmap->next();

        try {
            // $this->conveyor->send(?) ($locationDto, queue(?))         // + rabbitmq, wrapper(?)
        } catch (Exception $e) {
            // TODO
        }
    }
}
