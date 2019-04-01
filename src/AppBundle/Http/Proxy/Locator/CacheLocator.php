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

namespace Veslo\AppBundle\Http\Proxy\Locator;

use Veslo\AppBundle\Cache\Cacher;
use Veslo\AppBundle\Exception\Http\Proxy\Locator\BadProxyListUriException;
use Veslo\AppBundle\Http\Proxy\LocatorInterface;

/**
 * Uses cache to locate a proxy list
 */
class CacheLocator implements LocatorInterface
{
    /**
     * Saves a proxy list in the cache and invalidates it by demand
     *
     * @var Cacher
     */
    private $proxyCacher;

    /**
     * CacheLocator constructor.
     *
     * @param Cacher $proxyCacher Saves a proxy list in the cache and invalidates it by demand
     */
    public function __construct(Cacher $proxyCacher)
    {
        $this->proxyCacher = $proxyCacher;
    }

    /**
     * {@inheritdoc}
     *
     * @throws BadProxyListUriException
     */
    public function locate(): iterable
    {
        $proxyList = $this->proxyCacher->get();

        return $proxyList ?? [];
    }
}
