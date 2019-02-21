<?php

declare(strict_types=1);

namespace Veslo\AnthillBundle\Exception\Vacancy\Scanner;

use RuntimeException;
use Symfony\Component\HttpFoundation\Response;
use Throwable;
use Veslo\AnthillBundle\Exception\AnthillBundleExceptionInterface;

/**
 * Will be thrown if strategy for scanner is not found
 */
class StrategyNotFoundException extends RuntimeException implements AnthillBundleExceptionInterface
{
    /**
     * Default error message
     *
     * @const string
     */
    public const MESSAGE = 'Scanner strategy is not found.';

    /**
     * Error message with search strategy name
     *
     * @const string
     */
    public const MESSAGE_WITH_SEARCH_STRATEGY =
        "Compatible parse strategy for search algorithm '{searchStrategyName}' is not found."
        . ' Declare it in roadmaps.yml';

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
     * Returns exception in context of unspecified parse strategy for search algorithm
     *
     * @param string $searchStrategyName Name of search algorithm
     *
     * @return StrategyNotFoundException
     */
    public static function withSearchStrategyName(string $searchStrategyName): StrategyNotFoundException
    {
        $message = str_replace('{searchStrategyName}', $searchStrategyName, self::MESSAGE_WITH_SEARCH_STRATEGY);

        return new static($message);
    }
}
