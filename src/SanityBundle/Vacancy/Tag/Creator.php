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

namespace Veslo\SanityBundle\Vacancy\Tag;

use Veslo\AppBundle\Entity\Repository\BaseEntityRepository;
use Veslo\SanityBundle\Dto\Vacancy\Tag\GroupDto;
use Veslo\SanityBundle\Dto\Vacancy\TagDto;
use Veslo\SanityBundle\Entity\Vacancy\Tag;
use Veslo\SanityBundle\Entity\Vacancy\Tag\Group;
use Veslo\SanityBundle\Vacancy\Tag\Group\CreatorInterface as GroupCreatorInterface;

/**
 * Creates and persists a new sanity tag in the local storage
 */
class Creator
{
    /**
     * Creates and persists sanity tag groups in the local storage
     *
     * @var GroupCreatorInterface
     */
    private $groupCreator;

    /**
     * Repository where sanity tag entities are stored
     *
     * @var BaseEntityRepository
     */
    private $tagRepository;

    /**
     * Repository where sanity tag groups are stored
     *
     * @var BaseEntityRepository
     */
    private $groupRepository;

    /**
     * Creator constructor.
     *
     * @param GroupCreatorInterface $groupCreator    Creates and persists sanity tag groups in the local storage
     * @param BaseEntityRepository  $tagRepository   Repository where sanity tag entities are stored
     * @param BaseEntityRepository  $groupRepository Repository where sanity tag groups are stored
     */
    public function __construct(
        GroupCreatorInterface $groupCreator,
        BaseEntityRepository $tagRepository,
        BaseEntityRepository $groupRepository
    ) {
        $this->groupCreator    = $groupCreator;
        $this->tagRepository   = $tagRepository;
        $this->groupRepository = $groupRepository;
    }

    /**
     * Creates and returns a new sanity tag entity by specified data
     *
     * @param TagDto $tagData        Context of sanity tag data
     * @param bool   $isCascadeChild Whenever entity creation is just a part of the entity-owner creation and entity
     *                               manager should not be instantly flushed
     *
     * @return Tag
     */
    public function createByDto(TagDto $tagData, bool $isCascadeChild = false): Tag
    {
        $tag = new Tag();

        $tagName = $tagData->getName();
        $tag->setName($tagName);

        $tagTitle = $tagData->getTitle();
        $tag->setTitle($tagTitle);

        $tagDescription = $tagData->getDescription();
        $tag->setDescription($tagDescription);

        $tagColor = $tagData->getColor();
        $tag->setColor($tagColor);

        $tagImageUrl = $tagData->getImageUrl();
        $tag->setImageUrl($tagImageUrl);

        $groupData = $tagData->getGroup();
        $group     = $this->resolveGroup($groupData);

        $tag->setGroup($group);

        $this->tagRepository->save($tag, !$isCascadeChild);

        return $tag;
    }

    /**
     * Returns related group entity if exists or calls the group creator to build a new one
     *
     * @param GroupDto $groupData Context of vacancy tags group data
     *
     * @return Group
     */
    private function resolveGroup(GroupDto $groupData): Group
    {
        $groupName = $groupData->getName();
        $group     = $this->groupRepository->findOneByName($groupName);

        if (!$group instanceof Group) {
            $group = $this->groupCreator->createByDto($groupData, true);
        }

        return $group;
    }
}
