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

/**
 * Provides sanity tag groups which will be used by vacancy analysers
 */
interface ProviderInterface
{
    /**
     * Returns sanity tag groups used by vacancy analysers
     *
     * @return GroupDto[]
     */
    public function getTagGroups(): array;
}
