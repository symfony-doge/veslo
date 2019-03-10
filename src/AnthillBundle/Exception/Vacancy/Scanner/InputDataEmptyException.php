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

namespace Veslo\AnthillBundle\Exception\Vacancy\Scanner;

use RuntimeException;
use Symfony\Component\HttpFoundation\Response;
use Throwable;
use Veslo\AnthillBundle\Exception\AnthillBundleExceptionInterface;

/**
 * Will be thrown if strategy for scanner is not found
 */
class InputDataEmptyException extends RuntimeException implements AnthillBundleExceptionInterface
{
    /**
     * Default error message
     *
     * @const string
     */
    public const MESSAGE = 'Input data for parsing is empty.';

    /**
     * Error message with vacancy url
     *
     * @const string
     */
    public const MESSAGE_WITH_VACANCY_URL = "Data fetched from '{vacancyUrl}' is empty.";

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
     * Returns exception in context of vacancy URL
     *
     * @param string $vacancyUrl Vacancy URL
     *
     * @return InputDataEmptyException
     */
    public static function withVacancyUrl(string $vacancyUrl): InputDataEmptyException
    {
        $message = str_replace('{vacancyUrl}', $vacancyUrl, self::MESSAGE_WITH_VACANCY_URL);

        return new static($message);
    }
}
