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
use Veslo\AppBundle\Http\Proxy\Locator\StaticArrayLocator;

/**
 * Provides proxy for http requests
 */
class Alfred
{
    /**
     * Uses a simple array node from configuration file to provide proxy list
     *
     * @var StaticArrayLocator
     */
    private $proxyLocator;

    /**
     * Alfred constructor.
     *
     * @param StaticArrayLocator $proxyLocator Uses a simple array node from configuration file to provide proxy list
     */
    public function __construct(StaticArrayLocator $proxyLocator)
    {
        $this->proxyLocator = $proxyLocator;
    }

    /**
     * Returns proxy for http requests
     *
     * @return string
     */
    public function getProxy(): string
    {
        $proxies = $this->getProxyList();

        if (0 >= count($proxies)) {
            throw new ProxyNotFoundException();
        }

        $numericProxyList = array_values($proxies);

        $randomIndex = mt_rand(0, count($numericProxyList) - 1);
        $proxy       = $numericProxyList[$randomIndex];

        return $proxy;
    }

    /**
     * Returns list of all available proxies
     *
     * @return string[]
     */
    private function getProxyList(): array
    {
        return $this->proxyLocator->locate();
    }
}
