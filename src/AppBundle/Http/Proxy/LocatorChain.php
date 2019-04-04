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

namespace Veslo\AppBundle\Http\Proxy;

use Ds\PriorityQueue;
use Exception;
use Psr\Log\LoggerInterface;
use RuntimeException;

/**
 * Aggregates proxy locators and polls each of them one by one until proxy list is returned
 *
 * Note: although it is technically possible to use LocatorChain instance as a part of other locator, this class
 * is not directly designed to be nested one (so it is placed outside of locator's namespace and named unconventionally)
 */
class LocatorChain implements LocatorInterface
{
    /**
     * Dispatches a failed poll message for locators marked by "isImportant" flag
     *
     * @var LoggerInterface
     */
    private $logger;

    /**
     * Priority queue with proxy locators
     *
     * It is not really needed to use data structures from php-ds extension in this context due to low objects count,
     * it's mostly for learning purposes... also we have a little bonus - priority managing is delegated to the C-layer
     * instead of PHP code, so a compiler pass can be simplified.
     *
     * @var PriorityQueue<LocatorInterface>
     *
     * @see https://github.com/php-ds
     */
    private $proxyLocators;

    /**
     * Cached polling result for current process
     * (otherwise we should call a `PriorityQueue::copy()` and it can be redundant)
     *
     * @var string[]
     */
    private $_proxyList;

    /**
     * LocatorChain constructor.
     *
     * @param LoggerInterface $logger Dispatches a failed poll message for locators marked by "isImportant" flag
     */
    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;

        $this->proxyLocators = new PriorityQueue();
        $this->_proxyList    = null;
    }

    /**
     * Adds a proxy locator to the list for polling
     *
     * @param LocatorInterface $proxyLocator Provides a list with proxies available for http requests
     * @param int              $priority     Locator priority in polling loop
     * @param bool             $isImportant  Whenever a critical message should be raised if locator fails to locate a
     *                                       proxy list
     *
     * @return void
     */
    public function addLocator(LocatorInterface $proxyLocator, int $priority, bool $isImportant = false): void
    {
        if (null !== $this->_proxyList) {
            throw new RuntimeException('Unable to add a new locator after locate() execution.');
        }

        $this->proxyLocators->push([$proxyLocator, ['isImportant' => $isImportant]], $priority);
    }

    /**
     * {@inheritdoc}
     *
     * @see PriorityQueue::getIterator()
     */
    public function locate(): iterable
    {
        if (null !== $this->_proxyList) {
            return $this->_proxyList;
        }

        foreach ($this->proxyLocators as list($proxyLocator, $pollParameters)) {
            $proxyList = $this->poll($proxyLocator, $pollParameters);

            if (0 < count($proxyList)) {
                return $this->_proxyList = $proxyList;
            }
        }

        return $this->_proxyList = [];
    }

    /**
     * Polls locator for a proxy list
     *
     * @param LocatorInterface $proxyLocator Provides a list with proxies available for http requests
     * @param array            $parameters   Parameters for locator polling
     *
     * @return string[]
     */
    private function poll(LocatorInterface $proxyLocator, array $parameters): array
    {
        $locatorClass = get_class($proxyLocator);
        $isImportant  = $parameters['isImportant'];
        $pollContext  = ['locatorClass' => $locatorClass, 'isImportant' => $isImportant];

        $this->logger->debug('Polling locator for a proxy list.', $pollContext);

        $proxyList = [];

        try {
            $proxyList = $proxyLocator->locate();
        } catch (Exception $e) {
            $message          = $e->getMessage();
            $pollContextError = array_merge(['message' => $message], $pollContext);

            $this->logger->error(
                'An error has been occurred while polling locator for a proxy list.',
                $pollContextError
            );
        }

        $isProxyListFound = (0 < count($proxyList));

        if ($isProxyListFound) {
            $pollContextFound = array_merge(['proxies' => $proxyList], $pollContext);
            $this->logger->debug('Proxy list has been located.', $pollContextFound);

            return $proxyList;
        }

        if ($isImportant) {
            $this->logger->critical("Proxy locator with 'isImportant' flag didn't provide a proxy list.", $pollContext);
        }

        return [];
    }
}
