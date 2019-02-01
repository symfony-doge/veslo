<?php

namespace Veslo\AnthillBundle\Vacancy;

use Exception;
use Psr\Log\LoggerInterface;

/**
 * Digs some dung (vacancies) from internet and sends to queue for processing
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
     * @param RoadmapInterface $roadmap    Provides URL of vacancies
     * @param int              $iterations Digging iterations count, at least one
     *
     * @return void
     */
    public function dig(RoadmapInterface $roadmap, int $iterations = 1): void
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
     * @param RoadmapInterface $roadmap Provides URL of vacancies
     *
     * @return void
     */
    private function digIteration(RoadmapInterface $roadmap): void
    {
        // TODO: digging hard...
        sleep(5);

        if (!$roadmap->hasNext()) {
            $this->logger->log('info', 'No more vacancies to parse.', ['roadmap' => $roadmap]);

            return;
        }

        $vacancyUrl = $roadmap->next();

        try {
            // $vacancyDto = $this->vacancyParser->parse($vacancyUrl);  // + DTO: VacancyDto
            // $this->conveyor->send(?) ($vacancyDto, queue(?))         // + rabbitmq, wrapper(?)
        } catch (Exception $e) {
            // TODO
        }
    }
}
