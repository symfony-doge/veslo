<?php

namespace Veslo\AnthillBundle\Vacancy;

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
     * @param int $iterations Digging iterations count
     *
     * @return void
     */
    public function dig(int $iterations = 1): void
    {
        // TODO: digging hard...
        sleep(1);

        $this->logger->log('info', 'Digging iteration complete.');
    }
}
