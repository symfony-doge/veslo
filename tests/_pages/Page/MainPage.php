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

namespace Veslo\Tests\_pages\Page;

use Veslo\Tests\_pages\Page;

/**
 * Main page
 */
class MainPage extends Page
{
    /**
     * Page route
     *
     * @const string
     */
    private const ROUTE = '/';

    /**
     * {@inheritdoc}
     */
    protected static function getRoute(): string
    {
        return self::ROUTE;
    }
}
