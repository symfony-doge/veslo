<?php

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
