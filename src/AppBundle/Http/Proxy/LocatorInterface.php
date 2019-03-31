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

/**
 * Provides a list with available proxies for http requests
 */
interface LocatorInterface
{
    /**
     * Service tag for aggregation, e.g. in locator chain
     *
     * Flags:
     *     priority (int) - locator's priority, e.g. position in queue while polling by a decorator
     *     isImportant (bool) - whenever a critical message should be raised if locator fails to locate a proxy list
     *
     * @const string
     */
    public const TAG = 'veslo.app.http.proxy.locator';

    /**
     * Returns iterable data structure with all available proxies for requests, IP:PORT
     *
     * @return iterable|string[]
     */
    public function locate(): iterable;
}
