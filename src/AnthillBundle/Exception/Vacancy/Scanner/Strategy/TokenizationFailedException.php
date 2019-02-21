<?php

declare(strict_types=1);

namespace Veslo\AnthillBundle\Exception\Vacancy\Scanner\Strategy;

use RuntimeException;
use Symfony\Component\HttpFoundation\Response;
use Throwable;
use Veslo\AnthillBundle\Exception\AnthillBundleExceptionInterface;

/**
 * Will be thrown if vacancy data tokenization has failed, ex. some property path is not found in response
 */
class TokenizationFailedException extends RuntimeException implements AnthillBundleExceptionInterface
{
    /**
     * Default error message
     *
     * @const string
     */
    public const MESSAGE = 'Failed to extract vacancy data from website response.';

    /**
     * Error message if response from website is not as expected
     *
     * @const string
     */
    public const MESSAGE_UNEXPECTED_RESPONSE = "Bastardi! Dove sono le offerte di lavoro? ('{needle}' is missed)";

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
     * @return TokenizationFailedException
     */
    public static function withPrevious(Throwable $previous): TokenizationFailedException
    {
        return new static(self::MESSAGE, Response::HTTP_INTERNAL_SERVER_ERROR, $previous);
    }

    /**
     * Returns exception with context of missed key or path in response from website
     *
     * @param string $needle Key or path that is missed in response data structure
     *
     * @return TokenizationFailedException
     */
    public static function unexpectedResponse(string $needle): TokenizationFailedException
    {
        $message = str_replace('{needle}', $needle, self::MESSAGE_UNEXPECTED_RESPONSE);

        return new static($message);
    }
}
