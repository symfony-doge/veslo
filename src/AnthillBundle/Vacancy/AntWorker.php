<?php

namespace Veslo\AnthillBundle\Vacancy;

use Psr\Log\LoggerInterface;

/**
 * Collects dung (vacancies) from queue and sends to the Ministry of Truth for analysis
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
     * Logger
     *
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * AntWorker constructor.
     *
     * @param LoggerInterface $logger Logger
     */
    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    /**
     * Performs dung (vacancies) collecting iteration
     *
     * @return void
     */
    public function collect(): void
    {
        // TODO: collecting diligently...
        sleep(1);

        $this->logger->log('info', 'Collecting iteration complete.');
    }
}
