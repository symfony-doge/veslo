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

namespace Veslo\SanityBundle\Event\Listener\Vacancy\Tag\Group;

use Veslo\SanityBundle\Event\Vacancy\Tag\Group\SyncRequestedEvent;
use Veslo\SanityBundle\Vacancy\Tag\Group\Synchronizer;

/**
 * Calls a synchronizer(s) to retrieve actual sanity tags group information from third-party providers
 */
class SyncListener
{
    /**
     * Synchronizes sanity tags group data with external source
     *
     * @var Synchronizer
     */
    private $groupSynchronizer;

    /**
     * SyncListener constructor.
     *
     * @param Synchronizer $groupSynchronizer Synchronizes sanity tags group data with external source
     */
    public function __construct(Synchronizer $groupSynchronizer)
    {
        $this->groupSynchronizer = $groupSynchronizer;
    }

    /**
     * Performs sanity tag groups synchronization via configured synchronizer(s)
     *
     * @param SyncRequestedEvent $event Describes sanity tags group synchronization request
     *
     * @return void
     */
    public function onTagGroupsSync(SyncRequestedEvent $event): void
    {
        $isPersistAndFlush = $event->isPersistAndFlush();

        $diffSet = $this->groupSynchronizer->synchronize($isPersistAndFlush);

        foreach ($diffSet as $groupData) {
            $event->addTagGroup($groupData);
        }
    }
}
