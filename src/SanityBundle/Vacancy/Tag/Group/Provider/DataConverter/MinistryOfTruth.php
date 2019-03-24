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

namespace Veslo\SanityBundle\Vacancy\Tag\Group\Provider\DataConverter;

use SymfonyDoge\MinistryOfTruthClient\Dto\Response\Tag\Group\ContentDto as GroupData;
use Veslo\SanityBundle\Dto\Vacancy\Tag\GroupDto;

/**
 * Converts sanity tag groups data from external format to local data transfer objects
 */
class MinistryOfTruth
{
    /**
     * Returns array of sanity tag groups (DTOs) created by specified data in format of integration layer
     *
     * @param GroupData[] $groups Array of sanity tags group structures from external microservice
     *
     * @return GroupDto[]
     */
    public function convertTagGroups(array $groups): array
    {
        $groupDtoArray = [];

        foreach ($groups as $group) {
            $groupDtoArray[] = $this->convertTagGroup($group);
        }

        return $groupDtoArray;
    }

    /**
     * Returns sanity tags group dto created by specified data in format of integration layer
     *
     * @param GroupData $group Sanity tags group structure from external microservice
     *
     * @return GroupDto
     */
    public function convertTagGroup(GroupData $group): GroupDto
    {
        $groupDto = new GroupDto();

        $groupName = $group->getName();
        $groupDto->setName($groupName);

        $groupDescription = $group->getDescription();
        $groupDto->setDescription($groupDescription);

        $groupColor = $group->getColor();
        $groupDto->setColor($groupColor);

        return $groupDto;
    }
}
