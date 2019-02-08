<?php

namespace Veslo\AnthillBundle\Exception;

use RuntimeException;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class RoadmapConfigurationNotFoundException extends RuntimeException implements AnthillBundleExceptionInterface
{
    /**
     * Default error message
     *
     * @const string
     */
    public const MESSAGE = 'Roadmap configuration is not found.';

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
}
