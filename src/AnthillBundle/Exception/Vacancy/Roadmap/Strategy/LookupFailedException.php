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

namespace Veslo\AnthillBundle\Exception\Vacancy\Roadmap\Strategy;

use RuntimeException;
use Symfony\Component\HttpFoundation\Response;
use Throwable;
use Veslo\AnthillBundle\Exception\AnthillBundleExceptionInterface;

/**
 * Will be thrown if vacancy lookup algorithm on website has failed
 */
class LookupFailedException extends RuntimeException implements AnthillBundleExceptionInterface
{
    /**
     * Default error message
     *
     * @const string
     */
    public const MESSAGE = 'Failed to lookup vacancy on website.';

    /**
     * Error message if response from website is not as expected
     *
     * @const string
     */
    public const MESSAGE_UNEXPECTED_RESPONSE = "Bastardi! Dove sono le offerte di lavoro? ('{needle}' is missed)";

    /**
     * Error message if website response is unstable during a series of consecutive requests
     * Some algorithms are based on recursive calls with consistency checks
     *
     * @const string
     */
    public const MESSAGE_PROVIDER_IS_UNSTABLE = "Vacancy provider '{providerUri}' is unstable. Lets try again later.";

    /**
     * {@inheritdoc}
     */
    public function __construct(
        string $message = self::MESSAGE,
        int $code = Response::HTTP_INTERNAL_SERVER_ERROR,
        Throwable $previous = null
    ) {
        parent::__construct($message, $code, $previous);
    }

    /**
     * Returns exception with previous one
     *
     * @param Throwable $previous Previous exception
     *
     * @return LookupFailedException
     */
    public static function withPrevious(Throwable $previous): LookupFailedException
    {
        return new static(self::MESSAGE, Response::HTTP_INTERNAL_SERVER_ERROR, $previous);
    }

    /**
     * Returns exception with context of missed key or path in response from website
     *
     * @param string $needle Key or path that is missed in response data structure
     *
     * @return LookupFailedException
     */
    public static function unexpectedResponse(string $needle): LookupFailedException
    {
        $message = str_replace('{needle}', $needle, self::MESSAGE_UNEXPECTED_RESPONSE);

        return new static($message);
    }

    /**
     * Returns exception in context of unstable response from vacancy provider
     *
     * @param string $providerUri Vacancy provider's URI
     *
     * @return LookupFailedException
     */
    public static function providerIsUnstable(string $providerUri): LookupFailedException
    {
        $message = str_replace('{providerUri}', $providerUri, self::MESSAGE_PROVIDER_IS_UNSTABLE);

        return new static($message);
    }
}
