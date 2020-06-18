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

namespace Veslo\Tests\Acceptance\Vacancy;

use Veslo\Tests\AcceptanceTester;
use Veslo\Tests\_pages\Page\MainPage;

/**
 * History: User reads a vacancy
 */
class VacancyShowCest
{
    /**
     * Checks that show vacancy page works
     *
     * @param AcceptanceTester $I Acceptance tester
     *
     * @return void
     */
    public function vacancyShowWorks(AcceptanceTester $I): void
    {
        $I->wantTo('view a vacancy');

        $I->amGoingTo('open a vacancy list page');
        MainPage::isOpenedBy($I);

        $I->expectTo('see a vacancy list');
        $vacancyListSelector = '.vacancy-list';
        $I->seeElement($vacancyListSelector);

        $I->amGoingTo('click a vacancy link');
        $vacancyLinkSelector = '.vacancy-list-item:nth-child(3) .link-vacancy';
        $I->scrollTo($vacancyListSelector . ' ' . $vacancyLinkSelector);
        $I->click($vacancyLinkSelector, $vacancyListSelector);

        $I->expectTo('see a vacancy page');
        $I->see('Posted on');
    }
}
