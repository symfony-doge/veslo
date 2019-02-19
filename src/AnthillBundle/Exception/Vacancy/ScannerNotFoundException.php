<?php

declare(strict_types=1);

namespace Veslo\AnthillBundle\Exception\Vacancy;

use RuntimeException;
use Symfony\Component\HttpFoundation\Response;
use Throwable;
use Veslo\AnthillBundle\Exception\AnthillBundleExceptionInterface;

/**
 * Will be thrown if roadmap is not found
 */
class ScannerNotFoundException extends RuntimeException implements AnthillBundleExceptionInterface
{
    /**
     * Default error message
     *
     * @const string
     */
    public const MESSAGE = 'Scanner is not found.';

    /**
     * Error message for context of invalid location dto structure
     *
     * @const string
     */
    public const MESSAGE_INVALID_LOCATION = 'Invalid vacancy location structure. Roadmap is not specified.';

    /**
     * Error message with name of unsupported roadmap
     *
     * @const string
     */
    public const MESSAGE_WITH_ROADMAP_NAME = "Scanner for roadmap '{roadmapName}' is not found. Declare it in roadmaps.yml";

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
     * Returns exception in context of unspecified roadmap in location dto
     *
     * @return ScannerNotFoundException
     */
    public static function invalidLocation(): ScannerNotFoundException
    {
        return new static(self::MESSAGE_INVALID_LOCATION);
    }

    /**
     * Returns exception in context of unsupported roadmap
     *
     * @param string $roadmapName Unsupported roadmap name
     *
     * @return ScannerNotFoundException
     */
    public static function withRoadmapName(string $roadmapName): ScannerNotFoundException
    {
        $message = str_replace('{roadmapName}', $roadmapName, self::MESSAGE_WITH_ROADMAP_NAME);

        return new static($message);
    }
}
