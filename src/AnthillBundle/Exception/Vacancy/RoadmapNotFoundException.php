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

namespace Veslo\AnthillBundle\Exception\Vacancy;

use RuntimeException;
use Symfony\Component\HttpFoundation\Response;
use Throwable;
use Veslo\AnthillBundle\Exception\AnthillBundleExceptionInterface;

/**
 * Will be thrown if roadmap is not found
 */
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
     * @return RoadmapNotFoundException
     */
    public static function withName(string $roadmapName): RoadmapNotFoundException
    {
        $message = str_replace('{roadmapName}', $roadmapName, self::MESSAGE_WITH_NAME);

        return new static($message);
    }
}
