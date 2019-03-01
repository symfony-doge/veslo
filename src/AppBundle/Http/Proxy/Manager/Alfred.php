<?php

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

        if (empty($proxies) || array_keys($proxies) !== range(0, count($proxies) - 1)) {
            throw new ProxyNotFoundException();
        }

        $randomIndex = mt_rand(0, count($proxies) - 1);
        $proxy       = $proxies[$randomIndex];

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
