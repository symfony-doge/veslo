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

namespace Veslo\AnthillBundle\Fixture;

use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Veslo\AppBundle\Enum\Fixture\Group as ApplicationGroup;
use Veslo\AppBundle\Fixture\FileFixture;

/**
 * Fixture for vacancies w/ related companies & categories
 *
 * Merged in a single one unit to simplify work with relations
 * (alice requires a custom provider like "entity_reference()" for separated fixtures, which is redundant)
 *
 * @see https://github.com/nelmio/alice/blob/master/doc/relations-handling.md
 * @see https://github.com/nelmio/alice/issues/598
 */
class VacancyWithCompanyAndCategoriesFixture extends FileFixture implements FixtureGroupInterface
{
    /**
     * {@inheritdoc}
     */
    public static function getGroups(): array
    {
        return [
            ApplicationGroup::TEST,
        ];
    }
}
