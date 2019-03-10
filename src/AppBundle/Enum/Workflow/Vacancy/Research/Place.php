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

namespace Veslo\AppBundle\Enum\Workflow\Vacancy\Research;

/**
 * Dictionary of places for workflow
 */
final class Place
{
    /**
     * State represent founded vacancy, ready for parsing
     *
     * @const string
     */
    public const FOUND = 'found';

    /**
     * State represent parsed vacancy, ready for collecting
     *
     * @const string
     */
    public const PARSED = 'parsed';

    /**
     * State represent synchronized and collected vacancy, ready for indexing
     *
     * @const string
     */
    public const COLLECTED = 'collected';

    /**
     * State represent indexed vacancy
     *
     * @const string
     */
    public const INDEXED = 'indexed';
}
