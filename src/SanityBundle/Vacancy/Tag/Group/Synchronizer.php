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

/**
 * Synchronizes sanity tags group data with external source before entity creation
 */
class Synchronizer
{
    /**
     * Creates and persists sanity tag groups in the local storage
     *
     * @var CreatorInterface
     */
    private $groupCreator;

    /**
     * Provides sanity tag groups which will be used by vacancy analysers
     *
     * @var ProviderInterface
     */
    private $groupProvider;

    /**
     * Synchronizer constructor.
     *
     * @param CreatorInterface  $groupCreator  Creates and persists sanity tag groups in the local storage
     * @param ProviderInterface $groupProvider Provides sanity tag groups which will be used by vacancy analysers
     */
    public function __construct(CreatorInterface $groupCreator, ProviderInterface $groupProvider)
    {
        $this->groupCreator  = $groupCreator;
        $this->groupProvider = $groupProvider;
    }

    public function synchronize(): void
    {
        // TODO
    }
}
