<?php

namespace Veslo\AnthillBundle\Exception;

use RuntimeException;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class RoadmapNotFoundException extends RuntimeException implements AnthillBundleExceptionInterface
{
    /**
     * Default error message
     *
     * @const string
     */
    public const MESSAGE = 'Roadmap is not found.';

    /**
     * Error message with name of missed roadmap
     *
     * @const string
     */
    public const MESSAGE_WITH_NAME = "Roadmap '{roadmapName}' is not found. Declare it in roadmaps.yml";

    /**
     * {@inheritdoc}
     */
    public function __construct(string $message = self::MESSAGE, int $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }

    /**
     * Returns exception in context of specified name
     *
     * @param string $roadmapName Roadmap name
     *
     * @return RoadmapNotFoundException
     */
    public static function withName(string $roadmapName): RoadmapNotFoundException
    {
        $message = str_replace('{roadmapName}', $roadmapName, self::MESSAGE_WITH_NAME);

        return new static($message, Response::HTTP_INTERNAL_SERVER_ERROR);
    }
}
