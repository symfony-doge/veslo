<?php

declare(strict_types=1);

namespace Veslo\AppBundle\Exception\Http;

use RuntimeException;
use Symfony\Component\HttpFoundation\Response;
use Throwable;
use Veslo\AppBundle\Exception\AppBundleExceptionInterface;

/**
 * Will be thrown if proxy for request is not found
 */
class ProxyNotFoundException extends RuntimeException implements AppBundleExceptionInterface
{
    /**
     * Default error message
     *
     * @const string
     */
    public const MESSAGE = 'Proxy for http request is not found.';

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
