<?php

declare(strict_types=1);

namespace Veslo\AppBundle\Http\Proxy\Locator;

/**
 * Uses a simple array node from parameters file to provide proxy list
 */
class StaticArrayLocator
{
    /**
     * Array of available proxies for requests, IP:PORT
     *
     * @var string[]
     */
    private $proxyList;

    /**
     * StaticArrayLocator constructor.
     *
     * @param string[] $proxyList Array of available proxies for requests, IP:PORT
     */
    public function __construct(array $proxyList)
    {
        $this->proxyList = $proxyList;
    }

    /**
     * Returns array of available proxies for requests, IP:PORT
     *
     * @return string[]
     */
    public function locate(): array
    {
        return $this->proxyList;
    }
}
