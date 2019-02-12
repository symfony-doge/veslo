<?php

declare(strict_types=1);

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
     * @param int $iterations Collecting iterations count
     *
     * @return void
     */
    public function collect(int $iterations = 1): void
    {
        // TODO: collecting diligently...
        sleep(5);

        $this->logger->log('info', 'Collecting iteration complete.');
    }
}
