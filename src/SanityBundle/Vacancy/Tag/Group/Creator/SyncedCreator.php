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

use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Veslo\SanityBundle\Dto\Vacancy\Tag\GroupDto;
use Veslo\SanityBundle\Entity\Vacancy\Tag\Group;
use Veslo\SanityBundle\Event\Listener\Vacancy\Tag\Group\SyncListener;
use Veslo\SanityBundle\Event\Vacancy\Tag\Group\SyncRequestedEvent;
use Veslo\SanityBundle\Exception\Vacancy\Tag\Group\Creator\SyncedCreator\GroupForMergeNotFound;
use Veslo\SanityBundle\Vacancy\Tag\Group\CreatorInterface;

/**
 * Validates sanity tags group data against third-party providers via responsible synchronizer(s)
 */
class SyncedCreator implements CreatorInterface
{
    /**
     * Dispatches an event to registered listeners
     *
     * @var EventDispatcherInterface
     */
    private $eventDispatcher;

    /**
     * Creates and persists sanity tag groups in the local storage
     *
     * @var CreatorInterface
     */
    private $groupCreator;

    /**
     * SyncCreator constructor.
     *
     * @param EventDispatcherInterface $eventDispatcher Dispatches an event to registered listeners
     * @param CreatorInterface         $groupCreator    Creates and persists sanity tag groups in the local storage
     */
    public function __construct(EventDispatcherInterface $eventDispatcher, CreatorInterface $groupCreator)
    {
        $this->eventDispatcher = $eventDispatcher;
        $this->groupCreator    = $groupCreator;
    }

    /**
     * {@inheritdoc}
     *
     * @throws GroupForMergeNotFound
     *
     * @see SyncListener
     */
    public function createByDto(GroupDto $groupData, bool $isCascadeChild = false): Group
    {
        $syncRequestedEvent = new SyncRequestedEvent(false);
        $this->eventDispatcher->dispatch(SyncRequestedEvent::NAME, $syncRequestedEvent);
        $groupsDataSynced = $syncRequestedEvent->getTagGroups();

        $groupNameExpected = $groupData->getName();

        foreach ($groupsDataSynced as $groupDataSynced) {
            $groupName = $groupDataSynced->getName();

            if ($groupNameExpected !== $groupName) {
                continue 1;
            }

            return $this->merge($groupData, $groupDataSynced, $isCascadeChild);
        }

        throw GroupForMergeNotFound::withName($groupNameExpected);
    }

    /**
     * Merges group data provided for entity creation with data from external source
     * Can append an additional data from third-party sources
     *
     * @param GroupDto $groupData       Tags group data provided for entity creation
     * @param GroupDto $groupDataSynced Related data from group providers
     * @param bool     $isCascadeChild  Whenever entity creation is a part of the entity-owner creation, e.g. we can
     *                                  hint an entity manager to not perform instant flush after persist operation
     *
     * @return Group
     */
    private function merge(GroupDto $groupData, GroupDto $groupDataSynced, bool $isCascadeChild): Group
    {
        $groupDescription = $groupDataSynced->getDescription();
        $groupData->setDescription($groupDescription);

        $groupColor = $groupDataSynced->getColor();
        $groupData->setColor($groupColor);

        return $this->groupCreator->createByDto($groupData, $isCascadeChild);
    }
}
