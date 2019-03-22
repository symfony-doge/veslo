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

namespace Veslo\SanityBundle\Event\Vacancy\Tag\Group;

use Symfony\Component\EventDispatcher\Event;
use Veslo\SanityBundle\Dto\Vacancy\Tag\GroupDto;

/**
 * Describes sanity tags group synchronization request
 */
class SyncRequestedEvent extends Event
{
    /**
     * Event name
     *
     * @const string
     */
    public const NAME = 'veslo.sanity.event.vacancy.tag.group.sync_requested';

    /**
     * Whenever a synchronizer should save resolved data in the local storage, default true
     *
     * @var bool
     */
    private $persistAndFlush;

    /**
     * List of sanity tag groups data filled up by synchronizers (new / modified groups)
     *
     * @var GroupDto[]
     */
    private $tagGroups;

    /**
     * SyncRequestedEvent constructor.
     *
     * @param bool $persistAndFlush Whenever a synchronizer should save resolved data in the local storage
     */
    public function __construct(bool $persistAndFlush = true)
    {
        $this->persistAndFlush = $persistAndFlush;
        $this->tagGroups       = [];
    }

    /**
     * Returns positive if a synchronizer should save resolved data in the local storage, negative otherwise
     *
     * @return bool
     */
    public function isPersistAndFlush(): bool
    {
        return $this->persistAndFlush;
    }

    /**
     * Returns sanity tag groups data which are marked as new / modified by synchronizer(s)
     *
     * @return GroupDto[]
     */
    public function getTagGroups(): array
    {
        return $this->tagGroups;
    }

    /**
     * Adds a sanity tags group data, marked as new / modified
     *
     * @param GroupDto $group Sanity tags group data
     *
     * @return void
     */
    public function addTagGroup(GroupDto $group): void
    {
        $this->tagGroups[] = $group;
    }
}
