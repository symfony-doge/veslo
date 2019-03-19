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

namespace Veslo\AppBundle\Workflow\Vacancy\Worker\Iteration;

use Closure;
use Exception;
use Psr\Log\LoggerInterface;
use Veslo\AppBundle\Workflow\Vacancy\PitInterface;
use Veslo\AppBundle\Workflow\Vacancy\WorkerInterface;

/**
 * Represent common execution logic for a set of similar iterations
 */
class Loop
{
    /**
     * An executor of iteration
     *
     * @var WorkerInterface
     */
    private $worker;

    /**
     * A business logic to be executed multiple times
     *
     * @var Closure
     */
    private $iteration;

    /**
     * Message for an unsuccessful execution
     *
     * @var string
     */
    private $errorMessage;

    /**
     * Loop constructor.
     *
     * @param WorkerInterface $worker       An executor of iteration
     * @param Closure         $iteration    A business logic to be executed multiple times
     * @param string          $errorMessage Message for an unsuccessful execution
     */
    public function __construct(
        WorkerInterface $worker,
        Closure $iteration,
        string $errorMessage
    ) {
        $this->worker       = $worker;
        $this->iteration    = $iteration;
        $this->errorMessage = $errorMessage;
    }

    /**
     * Performs an iteration loop for executor's business logic
     *
     * @param PitInterface $source     Data source
     * @param int          $iterations Iterations count
     *
     * @return int Successful iterations count
     */
    public function execute(PitInterface $source, int $iterations): int
    {
        $iterationRemains = max(1, $iterations);
        $iterationSuccess = 0;
        $iterationClosure = $this->iteration;

        while ($iterationRemains > 0) {
            try {
                if ($iterationClosure($source)) {
                    ++$iterationSuccess;
                }
            } catch (Exception $e) {
                $logger = $this->worker->getLogger();

                if ($logger instanceof LoggerInterface) {
                    $context = ['source' => get_class($source), 'message' => $e->getMessage()];
                    $logger->error($this->errorMessage, $context);
                }
            }

            --$iterationRemains;
        }

        return $iterationSuccess;
    }
}
