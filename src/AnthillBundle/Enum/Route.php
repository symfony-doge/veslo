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

namespace Veslo\AnthillBundle\Enum;

/**
 * Dictionary of bundle routes for requests
 */
final class Route
{
    /**
     * Vacancy list action
     *
     * @const string
     */
    public const VACANCY_LIST = 'anthill_vacancy_list_page';

    /**
     * Vacancy list by category action
     *
     * @const string
     */
    public const VACANCY_LIST_BY_CATEGORY = 'anthill_vacancy_list_by_category_page';

    /**
     * Vacancy show action
     *
     * @const string
     */
    public const VACANCY_SHOW = 'anthill_vacancy_show';
}
