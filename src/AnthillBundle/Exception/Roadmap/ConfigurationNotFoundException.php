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
class ConfigurationNotFoundException extends RuntimeException implements AnthillBundleExceptionInterface
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
