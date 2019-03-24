<?php

/*
 * This file is part of the Veslo project <https://github.com/symfony-doge/veslo>.
 *
 * (C) 2019 Pavel Petrov <itnelo@gmail.com>.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @license https://opensource.org/licenses/GPL-3.0 GPL-3.0
 */

declare(strict_types=1);

namespace Veslo\SanityBundle\Exception\Vacancy\Tag\Group\Creator\SyncedCreator;

use RuntimeException;
use Symfony\Component\HttpFoundation\Response;
use Throwable;
use Veslo\SanityBundle\Exception\SanityBundleExceptionInterface;

/**
 * Will be thrown if group data is not found for creator merge operation during sync event
 */
class GroupForMergeNotFound extends RuntimeException implements SanityBundleExceptionInterface
{
    /**
     * Default error message
     *
     * @const string
     */
    public const MESSAGE = 'Group to be merged with is not found after a sync event.';

    /**
     * Error message with tags group name
     *
     * @const string
     */
    public const MESSAGE_WITH_NAME = "Group '{groupName}' to be merged with is not found after a sync event.";

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
     * Returns exception in context of specified group name
     *
     * @param string $groupName Tags group name
     *
     * @return GroupForMergeNotFound
     */
    public static function withName(string $groupName): GroupForMergeNotFound
    {
        $message = str_replace('{groupName}', $groupName, self::MESSAGE_WITH_NAME);

        return new static($message);
    }
}
