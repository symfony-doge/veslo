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

/**
 * Calls a synchronizer(s) to retrieve actual sanity tags group information from third-party providers
 */
class SyncListener
{
    public function onTagGroupsSync(SyncRequestedEvent $event): void
    {
        // TODO
    }
}
