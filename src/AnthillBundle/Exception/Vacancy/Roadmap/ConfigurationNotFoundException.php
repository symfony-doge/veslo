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

namespace Veslo\AnthillBundle\Exception\Vacancy\Roadmap;

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
     * Error message with key of missed configuration
     *
     * @const string
     */
    public const MESSAGE_WITH_KEY = "Configuration '{configurationKey}' for roadmap is not found.";

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
     * Returns exception in context of specified configuration key
     *
     * @param string $configurationKey Configuration key
     *
     * @return ConfigurationNotFoundException
     */
    public static function withKey(string $configurationKey): ConfigurationNotFoundException
    {
        $message = str_replace('{configurationKey}', $configurationKey, self::MESSAGE_WITH_KEY);

        return new static($message);
    }
}
