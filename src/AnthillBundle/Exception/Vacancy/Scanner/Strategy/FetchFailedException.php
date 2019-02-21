<?php

declare(strict_types=1);

namespace Veslo\AnthillBundle\Exception\Vacancy\Scanner\Strategy;

use RuntimeException;
use Symfony\Component\HttpFoundation\Response;
use Throwable;
use Veslo\AnthillBundle\Exception\AnthillBundleExceptionInterface;

/**
 * Will be thrown if vacancy data fetch failed for website
 */
class FetchFailedException extends RuntimeException implements AnthillBundleExceptionInterface
{
    /**
     * Default error message
     *
     * @const string
     */
    public const MESSAGE = 'Failed to fetch vacancy data from website.';

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
     * @return FetchFailedException
     */
    public static function withPrevious(Throwable $previous): FetchFailedException
    {
        return new static(self::MESSAGE, Response::HTTP_INTERNAL_SERVER_ERROR, $previous);
    }
}
