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

namespace Veslo\SanityBundle\Vacancy\Tag\Group;

use Veslo\SanityBundle\Dto\Vacancy\Tag\GroupDto;
use Veslo\SanityBundle\Entity\Vacancy\Tag\Group;

/**
 * Creates and persists sanity tag groups in the local storage
 */
interface CreatorInterface
{
    /**
     * Creates and returns a new group of sanity tags by specified data
     *
     * @param GroupDto $groupData      Context of sanity tag group data
     * @param bool     $isCascadeChild Whenever entity creation is a part of the entity-owner creation, e.g. we can hint
     *                                 an entity manager to not perform instant flush after persist operation
     *
     * @return Group
     */
    public function createByDto(GroupDto $groupData, bool $isCascadeChild = false): Group;
}
