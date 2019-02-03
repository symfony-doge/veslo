<?php

namespace Veslo\AnthillBundle\Exception;

use RuntimeException;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class RoadmapConfigurationNotSupportedException extends RuntimeException implements AnthillBundleExceptionInterface
{
    /**
     * Default error message
     *
     * @const string
     */
    public const MESSAGE = 'Configuration of roadmap is not supported.';

    /**
     * Error message with name of roadmap that doesn't support configuration
     *
     * @const string
     */
    public const MESSAGE_WITH_NAME = "Configuration of roadmap '{roadmapName}' is not supported.";

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
     * Returns exception in context of specified name
     *
     * @param string $roadmapName Roadmap name
     *
     * @return RoadmapConfigurationNotSupportedException
     */
    public static function withName(string $roadmapName): RoadmapConfigurationNotSupportedException
    {
        $message = str_replace('{roadmapName}', $roadmapName, self::MESSAGE_WITH_NAME);

        return new static($message);
    }
}
