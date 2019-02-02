<?php

namespace Veslo\AppBundle\Exception;

use RuntimeException;
use Symfony\Component\HttpFoundation\Response;
use Throwable;
use Veslo\AppBundle\Entity\Repository\BaseRepository;

/**
 * Will be thrown if entity is not found
 *
 * @see BaseRepository
 */
class EntityNotFoundException extends RuntimeException implements AppBundleExceptionInterface
{
    /**
     * Default error message
     *
     * @const string
     */
    public const MESSAGE = 'Entity is not found.';

    /**
     * Error message with class name and identifier
     *
     * @const string
     */
    public const MESSAGE_WITH_CLASS_ID = "Entity '{class}' with identifier '{id}' is not found.";

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
     * Returns exception in context of search by identifier
     *
     * @param string $class The name of the entity class.
     * @param mixed  $id    The identifier.
     *
     * @return EntityNotFoundException
     */
    public static function withClassAndId(string $class, $id): EntityNotFoundException
    {
        $message = str_replace(['{class}', '{id}'], [$class, strval($id)], self::MESSAGE_WITH_CLASS_ID);

        return new static($message);
    }
}
