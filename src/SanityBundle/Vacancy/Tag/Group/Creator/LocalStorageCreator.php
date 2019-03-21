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

namespace Veslo\SanityBundle\Vacancy\Tag\Group\Creator;

use Veslo\AppBundle\Entity\Repository\BaseEntityRepository;
use Veslo\SanityBundle\Dto\Vacancy\Tag\GroupDto;
use Veslo\SanityBundle\Entity\Vacancy\Tag\Group;
use Veslo\SanityBundle\Vacancy\Tag\Group\CreatorInterface;

/**
 * Creates and persists a group of sanity tags in the local storage
 */
class LocalStorageCreator implements CreatorInterface
{
    /**
     * Repository where sanity tag groups are stored
     *
     * @var BaseEntityRepository
     */
    private $groupRepository;

    /**
     * LocalStorageCreator constructor.
     *
     * @param BaseEntityRepository $groupRepository Repository where sanity tag groups are stored
     */
    public function __construct(BaseEntityRepository $groupRepository)
    {
        $this->groupRepository = $groupRepository;
    }

    /**
     * {@inheritdoc}
     */
    public function createByDto(GroupDto $groupData, bool $isCascadeChild = false): Group
    {
        $group = new Group();

        $groupName = $groupData->getName();
        $group->setName($groupName);

        $groupDescription = $groupData->getDescription();
        $group->setDescription($groupDescription);

        $groupColor = $groupData->getColor();
        $group->setColor($groupColor);

        $this->groupRepository->save($group, !$isCascadeChild);

        return $group;
    }
}
