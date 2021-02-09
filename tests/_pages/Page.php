<?php

/*
 * This file is part of the Veslo project <https://github.com/symfony-doge/veslo>.
 *
 * (C) 2019-2021 Pavel Petrov <itnelo@gmail.com>.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @license https://opensource.org/licenses/GPL-3.0 GPL-3.0
 */

declare(strict_types=1);

namespace Veslo\Tests\_pages;

use Codeception\Util\HttpCode;
use Veslo\Tests\AcceptanceTester;

/**
 * Abstract page for acceptance suite
 * Used to encapsulate actor's common actions on website such as navigation, registration, login, etc.
 */
abstract class Page
{
    /**
     * Navigates to the page
     *
     * @param AcceptanceTester $I Acceptance tester
     *
     * @return void
     */
    public static function isOpenedBy(AcceptanceTester $I): void
    {
        $pageRoute = static::getRoute();

        $I->amOnPage($pageRoute);
        $I->dontSee(404);
    }

    /**
     * Returns route for page
     *
     * @return string
     */
    abstract protected static function getRoute(): string;
}
