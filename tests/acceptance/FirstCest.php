<?php

namespace Veslo;

class FirstCest
{
    public function homepageWorks(AcceptanceTester $I)
    {
        $I->amOnPage('/');
        $I->see('Under development.');
    }
}
