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

namespace Veslo\AppBundle\Exception\Http\Proxy\Locator;

use RuntimeException;
use Throwable;
use Veslo\AppBundle\Exception\AppBundleExceptionInterface;

/**
 * Will be thrown if locator isn't able to get contents of specified proxy list URI
 */
class BadProxyListUriException extends RuntimeException implements AppBundleExceptionInterface
{
    /**
     * Default error message
     *
     * @const string
     */
    public const MESSAGE = 'Cannot locate a proxy list by specified URI.';

    /**
     * Error message with proxy list URI
     *
     * @const string
     */
    public const MESSAGE_WITH_PROXY_LIST_URI = "Cannot locate a proxy list by specified URI '{proxyListUri}'.";

    /**
     * {@inheritdoc}
     */
    public function __construct(string $message = self::MESSAGE, int $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }

    /**
     * Returns exception in context of specified proxy list URI
     *
     * @param string $proxyListUri Proxy list URI
     *
     * @return BadProxyListUriException
     */
    public static function withProxyListUri(string $proxyListUri): BadProxyListUriException
    {
        $message = str_replace('{proxyListUri}', $proxyListUri, self::MESSAGE_WITH_PROXY_LIST_URI);

        return new static($message);
    }
}
