<?php

declare(strict_types=1);

namespace Veslo\AppBundle\Exception\Workflow\Conveyor;

use Exception;
use RuntimeException;
use Symfony\Component\HttpFoundation\Response;
use Throwable;
use Veslo\AppBundle\Exception\AppBundleExceptionInterface;

/**
 * Will be thrown if conveyor failed to connect to message broker
 */
class ConnectionFailedException extends RuntimeException implements AppBundleExceptionInterface
{
    /**
     * Default error message
     *
     * @const string
     */
    public const MESSAGE = 'Connection to message broker is failed.';

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
     * @param Exception $previous Previous exception
     *
     * @return ConnectionFailedException
     */
    public static function withPrevious(Exception $previous): ConnectionFailedException
    {
        return new static(self::MESSAGE, Response::HTTP_INTERNAL_SERVER_ERROR, $previous);
    }
}
