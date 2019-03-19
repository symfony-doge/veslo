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

use Psr\Log\LoggerInterface;
use Veslo\AppBundle\Workflow\Vacancy\WorkerInterface;

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
    protected $logger;

    /**
     * Cockroach constructor.
     *
     * @param LoggerInterface $logger Logger
     */
    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    /**
     * Performs vacancy deliver iteration
     *
     * @return int
     */
    public function deliver(): int
    {
        // TODO: deliver faithfully...
        sleep(5);

        $this->logger->log('info', 'Delivering iteration complete.');

        return 0;
    }

    /**
     * {@inheritdoc}
     */
    public function getLogger(): ?LoggerInterface
    {
        return $this->logger;
    }
}
