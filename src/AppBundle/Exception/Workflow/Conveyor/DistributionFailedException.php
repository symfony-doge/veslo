<?php

declare(strict_types=1);

namespace Veslo\AppBundle\Exception\Workflow\Conveyor;

use RuntimeException;
use Symfony\Component\HttpFoundation\Response;
use Throwable;
use Veslo\AppBundle\Exception\AppBundleExceptionInterface;

/**
 * Will be thrown if conveyor failed payload distribution among the queues
 */
class DistributionFailedException extends RuntimeException implements AppBundleExceptionInterface
{
    /**
     * Default error message
     *
     * @const string
     */
    public const MESSAGE = 'Payload distribution is failed.';

    /**
     * Error message with queue name
     *
     * @const string
     */
    public const MESSAGE_WITH_QUEUE = "Payload distribution to queue '{queueName}' is failed.";

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
     * Returns exception in context of failed enqueue
     *
     * @param string $queueName Queue name in message broker
     *
     * @return DistributionFailedException
     */
    public static function withQueueName(string $queueName): DistributionFailedException
    {
        $message = str_replace(['{queueName}'], $queueName, self::MESSAGE_WITH_QUEUE);

        return new static($message);
    }
}
