<?php

declare(strict_types=1);

namespace Veslo\AnthillBundle\Exception\Roadmap;

use RuntimeException;
use Symfony\Component\HttpFoundation\Response;
use Throwable;
use Veslo\AnthillBundle\Exception\AnthillBundleExceptionInterface;

/**
 * Will be thrown if configuration for roadmap is not found
 */
class StrategyException extends RuntimeException implements AnthillBundleExceptionInterface
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
     * @return StrategyException
     */
    public static function withPrevious(Throwable $previous): StrategyException
    {
        return new static(self::MESSAGE, Response::HTTP_INTERNAL_SERVER_ERROR, $previous);
    }

    /**
     * Returns exception with context of missed key or path in response from website
     *
     * @param string $needle Key or path that is missed in response data structure
     *
     * @return StrategyException
     */
    public static function unexpectedResponse(string $needle): StrategyException
    {
        $message = str_replace('{needle}', $needle, self::MESSAGE_UNEXPECTED_RESPONSE);

        return new static($message);
    }

    /**
     * Returns exception in context of unstable response from vacancy provider
     *
     * @param string $providerUri Vacancy provider's URI
     *
     * @return StrategyException
     */
    public static function providerIsUnstable(string $providerUri): StrategyException
    {
        $message = str_replace('{providerUri}', $providerUri, self::MESSAGE_PROVIDER_IS_UNSTABLE);

        return new static($message);
    }
}
