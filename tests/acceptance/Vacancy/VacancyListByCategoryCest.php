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
 * History: User reads a category with vacancies
 *
 * - Checks that we can click on a category link and see a list by category page
 */
class VacancyListByCategoryCest
{
    /**
     * Checks that vacancy list by category page works
     *
     * @param AcceptanceTester $I Acceptance tester
     *
     * @return void
     */
    public function vacancyListByCategoryWorks(AcceptanceTester $I): void
    {
        $I->wantTo('see a category with vacancies');

        $I->amGoingTo('open a vacancy list page');
        MainPage::isOpenedBy($I);

        $I->expectTo('see a vacancy list');
        $vacancyListSelector = '.vacancy-list';
        $I->seeElement($vacancyListSelector);

        $I->amGoingTo('click a vacancy category link');
        $categoryLinkSelector = '.link-vacancy-category';
        $I->scrollTo($vacancyListSelector . ' ' . $categoryLinkSelector);
        $categoryName = $I->grabTextFrom($categoryLinkSelector);
        $categorySelectedXPath = "//*[contains(@class, 'nav-categories')][//*[contains(@class, 'active')]//*[contains(text(), '$categoryName')]]";
        $I->dontSeeElement($categorySelectedXPath);

        $I->click($categoryLinkSelector, $vacancyListSelector);

        $I->expectTo('see a vacancy list for a chosen category');
        $I->seeElement($categorySelectedXPath);
    }
}
