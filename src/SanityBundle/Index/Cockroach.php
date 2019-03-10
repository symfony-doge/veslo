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

namespace Veslo\SanityBundle\Index;

use Psr\Log\LoggerInterface;

/**
 * Delivers vacancy to the Ministry of Truth for analysis and sanity index evaluation
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
class Cockroach
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
     * @return void
     */
    public function deliver(): void
    {
        // TODO: deliver faithfully...
        sleep(5);

        $this->logger->log('info', 'Delivering iteration complete.');
    }
}
