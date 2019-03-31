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

namespace Veslo\AppBundle\Http\Proxy\Manager;

use Veslo\AppBundle\Exception\Http\ProxyNotFoundException;
use Veslo\AppBundle\Http\Proxy\LocatorInterface;

/**
 * Provides a proxy for http requests
 */
class Alfred
{
    /**
     * Provides a list with available proxies for http requests
     *
     * @var LocatorInterface
     */
    private $proxyLocator;

    /**
     * Alfred constructor.
     *
     * @param LocatorInterface $proxyLocator Uses a simple array node from configuration file to provide proxy list
     */
    public function __construct(LocatorInterface $proxyLocator)
    {
        $this->proxyLocator = $proxyLocator;
    }

    /**
     * Returns proxy for http requests
     *
     * @return string
     *
     * @throws ProxyNotFoundException
     */
    public function getProxy(): string
    {
        $numericProxyList = $this->resolveProxyList();

        if (0 >= count($numericProxyList)) {
            throw new ProxyNotFoundException();
        }

        $randomIndex = mt_rand(0, count($numericProxyList) - 1);
        $proxy       = $numericProxyList[$randomIndex];

        return $proxy;
    }

    /**
     * Returns numeric array with all available proxies
     *
     * @return string[]
     */
    private function resolveProxyList(): array
    {
        $proxyList        = $this->proxyLocator->locate(); // iterable
        $numericProxyList = [];

        foreach ($proxyList as $proxyUri) {
            $numericProxyList[] = $proxyUri;
        }

        return $numericProxyList;
    }
}
