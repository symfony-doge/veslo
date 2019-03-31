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

use Veslo\AppBundle\Http\Proxy\LocatorInterface;

/**
 * Uses a simple array node from parameters file to provide proxy list
 */
final class StaticArrayLocator implements LocatorInterface
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
     * {@inheritdoc}
     */
    public function locate(): iterable
    {
        return $this->proxyList;
    }
}
