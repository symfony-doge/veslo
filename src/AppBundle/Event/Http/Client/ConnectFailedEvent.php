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

namespace Veslo\AppBundle\Event\Http\Client;

use GuzzleHttp\ClientInterface as HttpClientInterface;
use GuzzleHttp\Exception\ConnectException;
use Symfony\Component\EventDispatcher\Event;
use Veslo\AppBundle\Http\Client\Batman;

/**
 * Some HTTP clients can propagate it whenever they failed to establish a connection to website
 *
 * @see Batman::request()
 */
class ConnectFailedEvent extends Event
{
    /**
     * Event name
     *
     * @const string
     */
    public const NAME = 'veslo.app.event.http.client.connect_failed';

    /**
     * The HTTP Client that failed to send a request
     *
     * @var HttpClientInterface
     */
    private $client;

    /**
     * Connect exception to analyse
     *
     * @var ConnectException
     */
    private $connectException;

    /**
     * ConnectFailedEvent constructor.
     *
     * @param HttpClientInterface $client           The HTTP Client that failed to send a request
     * @param ConnectException    $connectException Connect exception to analyse
     */
    public function __construct(HttpClientInterface $client, ConnectException $connectException)
    {
        $this->client           = $client;
        $this->connectException = $connectException;
    }

    /**
     * Returns the HTTP Client that failed to send a request
     *
     * @return HttpClientInterface
     */
    public function getClient(): HttpClientInterface
    {
        return $this->client;
    }

    /**
     * Returns a connect exception to analyse
     *
     * @return ConnectException
     */
    public function getConnectException(): ConnectException
    {
        return $this->connectException;
    }
}
