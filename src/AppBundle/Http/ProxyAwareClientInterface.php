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

namespace Veslo\AppBundle\Http;

/**
 * Marker for HTTP client that uses a proxy for sending requests
 */
interface ProxyAwareClientInterface
{
    /**
     * Returns positive whenever a proxy is enabled for a client
     *
     * @return bool
     */
    public function isProxyEnabled(): bool;
}
