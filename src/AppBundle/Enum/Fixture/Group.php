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

namespace Veslo\AppBundle\Enum\Fixture;

/**
 * Dictionary of common fixture groups
 */
final class Group
{
    /**
     * Tagged fixtures should be safe for execution in production environment
     * This is usually applicable to static data
     *
     * @const string
     */
    public const PRODUCTION = 'prod';

    /**
     * Tagged fixtures should be executed in dev or test environment only
     *
     * @const string
     */
    public const TEST = 'test';
}
