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

use Veslo\AppBundle\Entity\Repository\BaseEntityRepository;
use Veslo\SanityBundle\Dto\Vacancy\Tag\GroupDto;
use Veslo\SanityBundle\Entity\Vacancy\Tag\Group;

/**
 * Synchronizes sanity tags group data with external source
 */
class Synchronizer
{
    /**
     * Provides sanity tag groups which will be used by vacancy analysers
     *
     * @var ProviderInterface
     */
    private $groupProvider;

    /**
     * Creates and persists sanity tag groups in the local storage
     *
     * @var CreatorInterface
     */
    private $groupCreator;

    /**
     * Repository where sanity tag groups are stored
     *
     * @var BaseEntityRepository
     */
    private $groupRepository;

    /**
     * Synchronizer constructor.
     *
     * @param ProviderInterface    $groupProvider   Provides sanity tag groups which will be used by vacancy analysers
     * @param CreatorInterface     $groupCreator    Creates and persists sanity tag groups in the local storage
     * @param BaseEntityRepository $groupRepository Repository where sanity tag groups are stored
     */
    public function __construct(
        ProviderInterface $groupProvider,
        CreatorInterface $groupCreator,
        BaseEntityRepository $groupRepository
    ) {
        $this->groupProvider   = $groupProvider;
        $this->groupCreator    = $groupCreator;
        $this->groupRepository = $groupRepository;
    }

    /**
     * Performs sanity tag groups sync and returns a set which are not exists yet in the local storage (diff set)
     *
     * @param bool $isPersistAndFlush Whenever groups which are not exists in the local storage should be saved
     *
     * @return iterable|GroupDto[]
     */
    public function synchronize(bool $isPersistAndFlush = true): iterable
    {
        $groupsDataSynced = $this->groupProvider->getTagGroups(); // iterable

        /** @var Group[] $existentGroups */
        $existentGroups = $this->groupRepository->findAll();

        $diffSet = $this->calculateDiff($groupsDataSynced, $existentGroups);

        if ($isPersistAndFlush) {
            $this->persistAndFlush($diffSet);
        }

        return $diffSet;
    }

    /**
     * Calculates a diff set by specified synced groups data and existing ones
     *
     * @param iterable|GroupDto[] $groupsDataSynced Groups from third-party provider
     * @param Group[]             $existentGroups   Existing group entities in the local storage
     *
     * @return array
     */
    private function calculateDiff(iterable $groupsDataSynced, array $existentGroups): array
    {
        $syncedMap = [];
        foreach ($groupsDataSynced as $groupDataSynced) {
            $syncedGroupName             = $groupDataSynced->getName();
            $syncedMap[$syncedGroupName] = $groupDataSynced;
        }

        $existsMap = [];
        foreach ($existentGroups as $group) {
            $groupName             = $group->getName();
            $existsMap[$groupName] = $group;
        }

        $diffSet = [];
        foreach ($syncedMap as $groupName => $groupData) {
            if (!array_key_exists($groupName, $existsMap)) {
                $diffSet[] = $groupData;
            }
        }

        return $diffSet;
    }

    /**
     * Calls the group creator to save groups from a diff set into the local storage
     *
     * @param GroupDto[] $diffSet Groups which are not exists in the local storage
     *
     * @return void
     */
    private function persistAndFlush(array $diffSet): void
    {
        foreach ($diffSet as $groupData) {
            $this->groupCreator->createByDto($groupData);
        }
    }
}
